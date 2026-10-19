<?php

require_once 'Handphone.php';

class FeaturePhone extends Handphone
{
    public function __construct($merk, $model)
    {
        parent::__construct($merk, $model);
    }

    public function nyalakan()
    {
        echo "Feature Phone " . $this->merk . " " . $this->model . " dinyalakan." . PHP_EOL;
    }

    public function matikan()
    {
        echo "Feature Phone " . $this->merk . " " . $this->model . " dimatikan." . PHP_EOL;
    }

    public function telepon($nomor)
    {
        echo "Melakukan panggilan suara ke nomor " . $nomor . PHP_EOL;
    }

    public function mainGameSnake()
    {
        echo "Memainkan game Snake." . PHP_EOL;
    }
}
