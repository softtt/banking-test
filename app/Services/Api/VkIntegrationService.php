<?php


namespace App\Services\Api;

use App\Models\ServiceFails;
use App\Services\Api\CurrencyService;
use App\Services\Api\Vk\VkApiClient;
use Carbon\Carbon;

class VkIntegrationService
{
    private $adapter;

    public function __construct()
    {
        $this->adapter = new VkApiClient();
    }

    public function setCurrenciesIntoPublicPage(): ?string
    {
        $currencies = $this->getCurrencyService()->getCurrenciesByDate(Carbon::today()->format('Y-m-d'));

        if (is_null($currencies)) {
            $serviceFail = new  ServiceFails();
            $serviceFail->reason = 'Can not load currencies';
            $serviceFail->save();
        }

        $message = '';
        foreach ($currencies as $currency) {
            $message .= $currency['name'] . '-' . $currency['rate'] . ' ';
        }

        return $this->adapter->sendPostToPublicPage($message);
    }


    private function getCurrencyService(): CurrencyService
    {
        return app(CurrencyService::class);
    }

    public function getPosts(): array
    {
        return json_decode($this->adapter->getWall(), 1);
    }
}
