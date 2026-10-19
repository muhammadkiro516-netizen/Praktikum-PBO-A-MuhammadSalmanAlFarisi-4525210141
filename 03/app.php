<?php

require_once 'BangunDatar.php';
require_once 'Lingkaran.php';
require_once 'Persegi.php';
require_once 'Segitiga.php';

$bd = new BangunDatar();

$bd->luas();
$bd->keliling();

// membuat objek lingkaran
$lk = new Lingkaran(15);
echo "Luas lingkaran: " . round($lk->luas(), 4) . PHP_EOL;
echo "Keliling lingkaran: " . round($lk->keliling(), 4) . PHP_EOL;

// membuat objek persegi
$pj = new Persegi(10);
echo "Luas Bujur Sangkar: " . $pj->luas() . PHP_EOL;
echo "Keliling Bujur Sangkar: " . $pj->keliling() . PHP_EOL;

// membuat objek segitiga
$sg = new Segitiga(10, 8);
echo "Luas Segitiga: " . $sg->luas() . PHP_EOL;

// class Segitiga tidak mendefinisikan keliling(), jadi yang terpanggil
// adalah keliling() milik parent class yaitu BangunDatar
$sg->keliling();
