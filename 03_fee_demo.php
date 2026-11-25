<?php
// Demo polymorphism: repository menyimpan Student apa pun, biaya dihitung oleh objeknya sendiri.
// Jalankan: php examples/03_fee_demo.php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';

$repo = new StudentRepository();
$repo->create(new RegularStudent('M01', 'Alya', 'Informatika', 5000000));
$repo->create(new ScholarshipStudent('M02', 'Bima', 'Sistem Informasi', 5000000, 40));
$repo->create(new InternationalStudent('M03', 'Chen', 'Informatika', 5000000));

foreach ($repo->all() as $student) {            // $student bertipe Student (parent)
    printf("%-28s | %-13s | Rp%s%s", $student->summary(), $student->getType(),
        number_format($student->calculateFee(), 0, ',', '.'), PHP_EOL);
}
