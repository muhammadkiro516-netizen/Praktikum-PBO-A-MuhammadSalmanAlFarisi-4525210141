<?php

class iPhone
{
    // Properties
    // warna/color dan storage/kapasitas penyimpanan
    private $color;
    private $storage;

    // Konstruktor di PHP namanya __construct
    // setiap objek yang dibuat harus diberi nilai color dan storage
    public function __construct($color, $storage)
    {
        $this->color = $color;
        $this->storage = $storage;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getStorage()
    {
        return $this->storage;
    }
}
