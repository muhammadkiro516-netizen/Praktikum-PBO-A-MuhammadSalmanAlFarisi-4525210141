<?php

require_once 'Dokter.php';
require_once 'Pasien.php';
require_once 'Pemain.php';
require_once 'Tim.php';
require_once 'Buku.php';

/**
 * ASOSIASI
 */
$dokter = new Dokter("Dr. Andi");
$pasien = new Pasien("Budi");

// Pak Dokter merawat Pasien
$dokter->merawat($pasien);

echo PHP_EOL;

/**
 * AGREGASI
 */
$pemain1 = new Pemain("Eko");
$pemain2 = new Pemain("Dina");

// membuat Tim dari pemain yang sudah ada
$tim = new Tim("Garuda", [$pemain1, $pemain2]);
$tim->tampilkanPemain();

echo PHP_EOL;

/**
 * KOMPOSISI
 */
$buku = new Buku("Belajar PHP");
$buku->tampilkanBab();

// kalau buku dihancurkan, bab juga ikut hilang
$buku = null;
