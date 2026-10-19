<?php

require_once 'Handphone.php';
require_once 'Smartphone.php';
require_once 'FeaturePhone.php';

// array yang isinya objek Handphone (Smartphone dan FeaturePhone)
$daftarHandphone = [];
$daftarHandphone[0] = new Smartphone("Samsung", "Galaxy S21");
$daftarHandphone[1] = new FeaturePhone("Nokia", "3310");

// loop untuk memanggil method secara polimorfik
// method yang sama hasilnya beda tergantung objeknya
foreach ($daftarHandphone as $hp) {
    $hp->nyalakan();
    $hp->telepon("08123456789");
    $hp->matikan();
    echo PHP_EOL;
}

// mengakses method khusus tiap class, dicek dulu pakai instanceof
foreach ($daftarHandphone as $hp) {
    if ($hp instanceof Smartphone) {
        $hp->aksesInternet();
    } elseif ($hp instanceof FeaturePhone) {
        $hp->mainGameSnake();
    }
}
