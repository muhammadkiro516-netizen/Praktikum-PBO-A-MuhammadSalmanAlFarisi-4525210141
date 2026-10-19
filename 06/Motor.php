<?php

require_once 'Vehicle.php';
require_once 'Movable.php';
require_once 'Fuelable.php';

class Motor extends Vehicle implements Fuelable, Movable
{
    // pakai refuel() default dari trait, jadi tidak perlu ditulis ulang
    use FuelableDefault;

    public function __construct($name)
    {
        parent::__construct($name);
    }

    public function move()
    {
        echo $this->name . " bergerak di tanah gravel." . PHP_EOL;
    }
}
