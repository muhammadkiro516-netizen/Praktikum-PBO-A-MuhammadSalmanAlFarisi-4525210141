<?php

require_once 'BangunDatar.php';

class Persegi extends BangunDatar
{
    private $sisi;

    public function __construct($sisi)
    {
        $this->sisi = $sisi;
    }

    public function luas()
    {
        return $this->sisi * $this->sisi;
    }

    public function keliling()
    {
        return $this->sisi * 4;
    }
}
