<?php

// Interface di PHP tidak boleh punya isi method (tidak ada default method
// seperti di Java), jadi interface-nya cuma kontrak saja.
interface Fuelable
{
    public function refuel();
}

// Default method refuel() dari Java dipindah ke trait.
// Class yang mau pakai versi default tinggal "use FuelableDefault"
trait FuelableDefault
{
    public function refuel()
    {
        echo "Mengisi bahan bakar umum." . PHP_EOL;
    }
}
