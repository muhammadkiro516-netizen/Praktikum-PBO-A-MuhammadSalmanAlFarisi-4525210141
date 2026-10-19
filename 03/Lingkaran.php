<?php

require_once 'BangunDatar.php';

class Lingkaran extends BangunDatar
{
    // r atau jari-jari
    private $r;

    public function __construct($r)
    {
        $this->r = $r;
    }

    public function luas()
    {
        return M_PI * $this->r * $this->r;
    }

    public function keliling()
    {
        return 2 * M_PI * $this->r;
    }
}
