<?php
// Demo showData() via __call() — pemanggilan valid dan tidak valid.
// Jalankan: php examples/02_showdata_demo.php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';

$p = new StudentPresenter();

echo "== Pemanggilan valid ==\n";
echo $p->showData('Ahmad') . PHP_EOL;
echo $p->showData('2026001', 'Ahmad') . PHP_EOL;
echo $p->showData('2530521075', 'Muhammad Kirei Abdillah', 'Teknik Informatika') . PHP_EOL;

echo "\n== Pemanggilan tidak valid ==\n";
$cases = [
    'showData()'                          => fn() => $p->showData(),
    'showData("a","b","c","d")'           => fn() => $p->showData('a', 'b', 'c', 'd'),
    'showData(123)'                       => fn() => $p->showData(123),
    'showData("M01","Alya",3.5)'          => fn() => $p->showData('M01', 'Alya', 3.5),
    'showData("M01",null,"TI")'           => fn() => $p->showData('M01', null, 'TI'),
    'tampilkan("x")  [method tak dikenal]' => fn() => $p->tampilkan('x'),
];
foreach ($cases as $label => $fn) {
    try { echo $label . ' -> ' . $fn() . PHP_EOL; }
    catch (Throwable $e) { echo $label . ' -> ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL; }
}
