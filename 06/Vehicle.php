<?php

// Abstract class
abstract class Vehicle
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    // method umum yang bisa dipakai semua kendaraan
    public function showInfo()
    {
        echo "Kendaraan: " . $this->name . PHP_EOL;
    }
}
