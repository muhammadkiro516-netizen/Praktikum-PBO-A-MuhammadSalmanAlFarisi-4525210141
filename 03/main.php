<?php

require_once 'MahasiswaInternational.php';

// tanpa parameter
$mhsInt1 = new MahasiswaInternational();
$mhsInt1->setNama("Paolo Dicanio");
$mhsInt1->setNim("INT12345");
$mhsInt1->setUmur(21);
$mhsInt1->setNegaraAsal("Italy");
$mhsInt1->tampilkanInfo();

echo PHP_EOL;

// 3 parameter (nama, nim, negara)
$mhsInt2 = new MahasiswaInternational("Sarah", "INT67890", "Australia");
$mhsInt2->setUmur(22);
$mhsInt2->tampilkanInfo();

echo PHP_EOL;

// 4 parameter (nama, nim, negara, umur)
$mhsInt3 = new MahasiswaInternational("David", "INT54321", "UK", 23);
$mhsInt3->tampilkanInfo();
