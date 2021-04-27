<?php


namespace App\Services\Api;


use App\Models\Currency;
use App\Models\Notification;
use App\Models\ServiceFails;
use App\Models\User;
use App\Services\Api\Exceptions\FailedCurrencyLoadingException;
use App\Services\Api\Translators\CurrencyListTranslator;
use App\Services\Api\Translators\CurrencyTranslator;
use Illuminate\Support\Carbon;

class CurrencyService
{
    const LOAD_CURRENCY_URL = 'http://cbr.ru/scripts/XML_daily.asp';
    const UNITED_STATES_DOLLAR_CODE = 'USD';
    const EURO_CODE = 'EUR';

    private $currencyListTranslator;
    private $currencyTranslator;

    public function __construct(
        CurrencyListTranslator $currencyListTranslator,
        CurrencyTranslator $currencyTranslator
    )
    {
        $this->currencyTranslator = $currencyTranslator;
        $this->currencyListTranslator = $currencyListTranslator;
    }


    private function LoadDailyCurrency(): object
    {
        $dailyCurrenciesXML = simplexml_load_string(file_get_contents(self::LOAD_CURRENCY_URL));

        if (is_null($dailyCurrenciesXML)) {
            $serviceFail = new  ServiceFails();
            $serviceFail->reason = 'Can not load list';
            $serviceFail->save();
            throw new FailedCurrencyLoadingException('Can not load currency list');
        }

        return $dailyCurrenciesXML;
    }

    private function prepareXMLForSave(): array
    {
        $dailyCurrenciesXML = $this->LoadDailyCurrency();
        $formattedCurrencies = array();

        foreach ($dailyCurrenciesXML->Valute as $valute) {
            if ($valute->CharCode == self::UNITED_STATES_DOLLAR_CODE || $valute->CharCode == self::EURO_CODE) {
                $formattedCurrencies[] = array(
                    'name' => $valute->Name,
                    'rate' => $valute->Value,
                    'code' => $valute->CharCode
                );
            }
        }

        return $formattedCurrencies;
    }

    public function saveDailyCurrencies(): void
    {
        $preparedData = $this->prepareXMLForSave();

        foreach ($preparedData as $currency) {
            if (is_null($this->getRecentCurrencyByCode($currency['code']))) {
                $this->saveCurrency($currency);
            }
        }
    }

    public function notifyUsers(string $message): void
    {
        $userIds = User::all('id')->modelKeys();
        $notifications = array();

        foreach ($userIds as $userId) {
            $notifications[] = array(
                'message' => $message,
                'user_id' => $userId,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            );
        }

        $chunks = array_chunk($notifications, 5000);

        foreach ($chunks as $chunk) {
            Notification::insert($chunk);
        }
    }

    public function getRecentCurrencyByCode(string $code): ?array
    {
        $currency = Currency::query()
            ->where('code', '=', $code)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$currency) {
            return null;
        }

        return $this->currencyTranslator->translate($currency)->toArray();
    }

    public function saveCurrency(array $data): int
    {
        $currency = new Currency();
        $currency->name = $data['name'];
        $currency->rate = $data['rate'];
        $currency->code = $data['code'];
        $currency->save();

        return $currency->id;
    }

    public function getCurrenciesList(int $limit, int $offset): array
    {
        return $this->currencyListTranslator->translate($limit, $offset);
    }

    public function getCurrenciesByDate($date): ?array
    {
        $currencies = Currency::query()
            ->whereDate('created_at', Carbon::createFromFormat('Y-m-d', $date))
            ->get();

        if (empty($currencies)) {
            return null;
        }

        $translatedCurrencies = array();

        foreach ($currencies as $currency) {
            $translatedCurrencies[] = $this->currencyTranslator->translate($currency)->toArray();
        }

        return $translatedCurrencies;
    }

    public function isCurrenciesChange(): bool
    {
        $yestardayCurrencies = $this->getCurrenciesByDate(Carbon::yesterday()->format('Y-m-d'));

        if (empty($yestardayCurrencies)) {
            return true;
        }

        $recentEuro = $this->getRecentCurrencyByCode(self::EURO_CODE);
        $recentUSD = $this->getRecentCurrencyByCode(self::UNITED_STATES_DOLLAR_CODE);

        foreach ($yestardayCurrencies as $currency) {

            if ($currency['code'] == self::EURO_CODE) {
                if ($currency['rate'] != $recentEuro['rate'])
                    return true;
            }

            if ($currency['code'] == self::UNITED_STATES_DOLLAR_CODE) {
                if ($currency['rate'] != $recentUSD)
                    return true;
            }
        }

        return false;
    }
}
