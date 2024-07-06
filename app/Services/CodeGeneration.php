<?php

namespace App\Services;

use App\Models\Client;
use Carbon\Carbon;

class CodeGeneration
{
    protected $stationCode;
    protected $userCount;
    protected $year;
    protected $randomNumber;

    public function __construct($stationCode = 'USR')
    {
        $this->stationCode = $stationCode;
        $this->userCount = $this->getNextUserCount();
        $this->randomNumber = $this->generateRandomNumber();
        $this->year = $this->getYear();
    }

    protected function getYear()
    {
        return Carbon::now()->format('y');
    }

    protected function getNextUserCount()
    {
        $userCount = Client::orderBy('id', 'desc')->count();
        $lastCount = $userCount ? $userCount : 0;

        return str_pad($lastCount + 1, 5, '0', STR_PAD_LEFT);
    }

    protected function generateRandomNumber()
    {
        return mt_rand(100, 999);
    }

    public function generateCode()
    {
        return "{$this->stationCode}-{$this->userCount}-{$this->randomNumber}";
    }

    public function generateCodeWithYear()
    {
        return "{$this->stationCode}-{$this->year}{$this->userCount}-{$this->randomNumber}";
    }
}
