<?php

class Bab
{
    private $judulBab;

    public function __construct($judulBab)
    {
        $this->judulBab = $judulBab;
    }

    public function getJudulBab()
    {
        return $this->judulBab;
    }
}
