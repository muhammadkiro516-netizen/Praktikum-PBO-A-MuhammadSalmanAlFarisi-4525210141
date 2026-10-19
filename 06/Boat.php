<?php

require_once 'Vehicle.php';
require_once 'Movable.php';
require_once 'Fuelable.php';

// Subclass lain dari Vehicle yang mengimplementasikan Movable dan Fuelable
class Boat extends Vehicle implements Movable, Fuelable
{
    public function __construct($name)
    {
        parent::__construct($name);
    }

    public function move()
    {
        echo $this->name . " bergerak di air." . PHP_EOL;
    }

    // override refuel milik default
    public function refuel()
    {
        echo $this->name . " mengisi bahan bakar solar khusus kapal." . PHP_EOL;
    }
}
