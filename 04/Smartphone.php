<?php

require_once 'Handphone.php';

class Smartphone extends Handphone
{
    public function __construct($merk, $model)
    {
        parent::__construct($merk, $model);
    }

    public function nyalakan()
    {
        echo "Smartphone " . $this->merk . " " . $this->model . " sedang booting." . PHP_EOL;
    }

    public function matikan()
    {
        echo "Smartphone " . $this->merk . " " . $this->model . " sedang shutdown." . PHP_EOL;
    }

    public function telepon($nomor)
    {
        echo "Melakukan panggilan video ke nomor " . $nomor . PHP_EOL;
    }

    public function aksesInternet()
    {
        echo "Mengakses internet melalui Smartphone." . PHP_EOL;
    }
}
