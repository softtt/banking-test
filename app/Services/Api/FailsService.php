<?php


namespace App\Services\Api;


use App\Models\ServiceFails;
use Carbon\Carbon;

class FailsService
{
    public function isServiceFailedToday()
    {
        $fails = ServiceFails::query()->whereDate('created_at', Carbon::today())->get();
        return $fails->count();
    }
}
