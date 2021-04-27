<?php

namespace App\Http\Controllers;

use App\Http\Requests\Currency\CurrencyByDateRequest;
use App\Services\Api\CurrencyService;
use Illuminate\Http\JsonResponse;


class CurrencyController extends Controller
{

    private function getApiService(): CurrencyService
    {
        return app(CurrencyService::class);
    }

    public function loadDaily(): JsonResponse
    {
        $this->getApiService()->saveDailyCurrencies();

        if ($this->getApiService()->isCurrenciesChange()) {
            $this->getApiService()->notifyUsers('Currencies has changed today. Just take a look on our web site');
        }

        return response()->json(array('loaded' => true));
    }

    public function index(): JsonResponse
    {
        return response()->json($this->getApiService()->getCurrenciesList(20, 0));
    }

    public function getCurrencyByDate(CurrencyByDateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $currencies = $this->getApiService()->getCurrenciesByDate($validatedData['date']);

        if (is_null($currencies)) {
            return response()->json('Currencies for this date is not set');
        }

        return response()->json($currencies);
    }

}
