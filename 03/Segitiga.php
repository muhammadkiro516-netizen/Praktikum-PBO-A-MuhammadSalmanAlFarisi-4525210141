<?php

require_once 'BangunDatar.php';

class Segitiga extends BangunDatar
{
    private $alas;
    private $tinggi;

    public function __construct($alas, $tinggi)
    {
        $this->alas = $alas;
        $this->tinggi = $tinggi;
    }

    public function luas()
    {
        return ($this->alas * $this->tinggi) / 2;
    }
}
