<?php

require_once 'Vehicle.php';

// Subclass dari Vehicle yang tidak bisa bergerak dan tidak perlu bahan bakar
class Building extends Vehicle
{
    public function __construct($name)
    {
        parent::__construct($name);
    }

    // Building tidak mengimplementasikan Movable atau Fuelable,
    // jadi tidak perlu move() dan refuel()
}
