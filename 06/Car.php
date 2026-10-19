<?php

require_once 'Vehicle.php';
require_once 'Movable.php';
require_once 'Fuelable.php';

// Subclass dari Vehicle yang mengimplementasikan Movable dan Fuelable
class Car extends Vehicle implements Movable, Fuelable
{
    public function __construct($name)
    {
        parent::__construct($name);
    }

    public function move()
    {
        echo $this->name . " bergerak di jalan." . PHP_EOL;
    }

    public function refuel()
    {
        echo $this->name . " mengisi bahan bakar mobil" . PHP_EOL;
    }
}
