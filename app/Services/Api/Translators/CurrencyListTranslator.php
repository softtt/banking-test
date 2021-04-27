<?php


namespace App\Services\Api\Translators;


use App\Models\Currency;
use Illuminate\Support\Collection;

class CurrencyListTranslator
{
    private $currencyTranslator;

    public function __construct()
    {
        $this->currencyTranslator = new CurrencyTranslator();
    }

    public function translate(int $limit, int $offset): array
    {
        $currencies = $this->getCurrencies($limit, $offset);
        $result = array();

        foreach ($currencies as $currency) {
            $result[] = $this->currencyTranslator->translate($currency)->toArray();
        }

        return $result;
    }

    private function getCurrencies(int $limit, int $offset): Collection
    {
        $currencies = Currency::query()
            ->limit($limit)
            ->skip($offset)
            ->get();

        return $currencies;
    }
}
