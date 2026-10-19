<?php

require_once 'Mahasiswa.php';

// constructor tanpa parameter (pakai nilai default)
$soja = new Mahasiswa();
$soja->tampilkanInfo();

// memberikan value ke property lewat setter
$soja->setNama("Soja Purnamasari");
echo "Nama : " . $soja->getNama() . PHP_EOL;

$soja->setNim("4523210104");
echo "NIM : " . $soja->getNim() . PHP_EOL;

$soja->setUmur(15);
echo "Umur : " . $soja->getUmur() . PHP_EOL;

// constructor lengkap
$nenden = new Mahasiswa("Nenden Nuraini", "4523210144", 17);
$nenden->tampilkanInfo();
