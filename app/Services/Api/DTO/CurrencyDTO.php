<?php


namespace App\Services\Api\DTO;


class CurrencyDTO
{
    private $id;
    private $name;
    private $rate;
    private $code;
    private $created_at;


    public function __construct(
        $id,
        $name,
        $rate,
        $code,
        $created_at
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->rate = $rate;
        $this->code = $code;
        $this->created_at = $created_at;
    }

    public function toArray(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'rate' => $this->rate,
            'code' => $this->code,
            'date' => $this->created_at
        );
    }
}
