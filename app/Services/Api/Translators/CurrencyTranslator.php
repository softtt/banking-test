<?php


namespace App\Services\Api\Translators;


use App\Models\Currency;
use App\Services\Api\DTO\CurrencyDTO;

class CurrencyTranslator
{
    public function translate(Currency $currency): CurrencyDTO
    {
        return new CurrencyDTO(
            $currency->id,
            $currency->name,
            $currency->rate,
            $currency->code,
            $currency->created_at
        );
    }
}
