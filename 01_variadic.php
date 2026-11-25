<?php
// Tugas 1: variadic PHP bukan method overloading tradisional.
// Jalankan: php examples/01_variadic.php
declare(strict_types=1);
require __DIR__.'/Calculator.php';

$calc = new Calculator();
echo 'add(10, 20)     = ' . $calc->add(10, 20) . PHP_EOL;      // 30
echo 'add(10, 20, 30) = ' . $calc->add(10, 20, 30) . PHP_EOL;  // 60

// Bukti: hanya ADA SATU deklarasi add().
$m = new ReflectionMethod(Calculator::class, 'add');
echo 'Jumlah deklarasi add() di class Calculator : ' . count(array_filter(
    (new ReflectionClass(Calculator::class))->getMethods(),
    fn(ReflectionMethod $x) => $x->getName() === 'add'
)) . PHP_EOL;
echo 'Parameter add() : ' . $m->getParameters()[0]->getName()
   . ' (variadic = ' . ($m->isVariadic() ? 'ya' : 'tidak') . ')' . PHP_EOL;

// Bandingkan dengan Java/C++: dua deklarasi add(int,int) dan add(int,int,int).
// Di PHP, dua deklarasi bernama sama di satu class -> Fatal error: Cannot redeclare
// (lihat experiments/exp_redeclare_method.php).
