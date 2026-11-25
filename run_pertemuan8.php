<?php
// Pengujian Pertemuan 8. Jalankan dari folder proyek:  php tests/run_pertemuan8.php
// Tidak mengubah tests/run.php (pengujian asli). Exit code 1 jika ada tes gagal.
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
require __DIR__.'/../examples/Calculator.php';

$results = [];
function t(string $id, string $label, callable $fn): void {
    global $results;
    try { $ok = (bool)$fn(); $note = ''; }
    catch (Throwable $e) { $ok = false; $note = get_class($e).': '.$e->getMessage(); }
    $results[] = [$id, $label, $ok, $note];
    printf("%-4s | %-5s | %s%s\n", $id, $ok ? 'PASS' : 'FAIL', $label, $note ? "  <- $note" : '');
}
/** Pastikan $fn melempar exception bertipe $class; kembalikan pesan exception. */
function throws(callable $fn, string $class, ?string &$msg = null): bool {
    try { $fn(); } catch (Throwable $e) { $msg = $e->getMessage(); return $e instanceof $class; }
    return false;
}
function near(float $a, float $b): bool { return abs($a - $b) < 0.01; }
/** Jalankan file eksperimen di proses PHP terpisah dan tangkap outputnya. */
function runExperiment(string $file): string {
    $cmd = escapeshellarg(PHP_BINARY).' -d display_errors=1 -d error_reporting=-1 '
         . escapeshellarg(__DIR__.'/../experiments/'.$file).' 2>&1';
    return (string)shell_exec($cmd);
}

$calc = new Calculator();
$p    = new StudentPresenter();

// --- Wajib (PDF bagian I) ---
t('T01a','add(10,20) = 30',                       fn() => $calc->add(10, 20) === 30);
t('T01b','add(10,20,30) = 60',                    fn() => $calc->add(10, 20, 30) === 60);
t('T02', 'showData("Ahmad") -> "Nama: Ahmad"',    fn() => $p->showData('Ahmad') === 'Nama: Ahmad');
t('T03', 'showData("2026001","Ahmad")',           fn() => $p->showData('2026001', 'Ahmad') === 'NIM: 2026001, Nama: Ahmad');
t('T04', 'showData() tanpa argumen -> InvalidArgumentException', fn() => throws(fn() => $p->showData(), InvalidArgumentException::class));
t('T05', 'method tidak dikenal -> BadMethodCallException',       fn() => throws(fn() => $p->tampilkan('x'), BadMethodCallException::class));
t('T06', 'RegularStudent fee = 5.000.000',        fn() => near((new RegularStudent('M01','Alya','Informatika',5000000))->calculateFee(), 5000000));
t('T07', 'ScholarshipStudent diskon 40% = 3.000.000', fn() => near((new ScholarshipStudent('M02','Bima','SI',5000000,40))->calculateFee(), 3000000));
t('T08a','diskon -1 ditolak',  fn() => throws(fn() => new ScholarshipStudent('M03','Cici','TI',5000000,-1),  InvalidArgumentException::class));
t('T08b','diskon 101 ditolak', fn() => throws(fn() => new ScholarshipStudent('M03','Cici','TI',5000000,101), InvalidArgumentException::class));
t('T09', 'summary() sesuai tipe objek (3 jenis)', function () {
    $r = new RegularStudent('M01','Alya','Informatika',5000000);
    $s = new ScholarshipStudent('M02','Bima','SI',5000000,40);
    $i = new InternationalStudent('M03','Chen','TI',5000000);
    return $r->summary() === 'M01 - Alya'
        && $s->summary() === 'M02 - Bima [Beasiswa]'
        && $i->summary() === 'M03 - Chen [Internasional]';
});
t('T10', 'override method final -> Fatal error (file eksperimen terpisah)', function () {
    $o = runExperiment('exp_final_override.php');
    return stripos($o, 'final method') !== false && stripos($o, 'Student::getId') !== false
        && stripos($o, 'tidak akan tercapai') === false;
});

// --- Tugas 2: showData(id, name, major) ---
t('T11', 'showData(id,name,major) tiga string valid', fn() => $p->showData('M01','Alya','Informatika') === 'NIM: M01, Nama: Alya, Prodi: Informatika');
t('T12', '4 argumen ditolak (InvalidArgumentException)', fn() => throws(fn() => $p->showData('a','b','c','d'), InvalidArgumentException::class, $m) && str_contains($m, 'menerima 4'));
t('T13', 'tipe bukan string ditolak: showData(123)',    fn() => throws(fn() => $p->showData(123), InvalidArgumentException::class, $m) && str_contains($m, 'int'));
t('T14', 'tipe campuran ditolak: showData("M01","Alya",3.5)', fn() => throws(fn() => $p->showData('M01','Alya',3.5), InvalidArgumentException::class, $m) && str_contains($m, 'Argumen ke-3') && str_contains($m, 'float'));
t('T15', 'null ditolak: showData("M01",null,"TI")',      fn() => throws(fn() => $p->showData('M01', null, 'TI'), InvalidArgumentException::class));
t('T16', 'named argument ditolak',                       fn() => throws(fn() => $p->showData(name: 'Alya'), InvalidArgumentException::class));

// --- Tugas 3: InternationalStudent + polymorphism ---
t('T17', 'InternationalStudent fee = 5jt x 1,5 + 1jt = 8.500.000', fn() => near((new InternationalStudent('M04','Dewi','TI',5000000))->calculateFee(), 8500000));
t('T18', 'InternationalStudent is-a Student',            fn() => (new InternationalStudent('M04','Dewi','TI',5000000)) instanceof Student);
t('T19', 'repository menyimpan 3 jenis lewat tipe Student & biaya via polymorphism', function () {
    $repo = new StudentRepository();
    $repo->create(new RegularStudent('M01','Alya','Informatika',5000000));
    $repo->create(new ScholarshipStudent('M02','Bima','SI',5000000,40));
    $repo->create(new InternationalStudent('M03','Chen','TI',5000000));
    $fees = array_map(fn(Student $s) => $s->calculateFee(), $repo->all());
    return count($fees) === 3 && near($fees[0],5000000) && near($fees[1],3000000) && near($fees[2],8500000);
});
t('T20', 'StudentRepository tidak menyebut nama child class', function () {
    $src = (string)file_get_contents(__DIR__.'/../src/StudentRepository.php');
    foreach (['RegularStudent','ScholarshipStudent','InternationalStudent','instanceof'] as $w) {
        if (stripos($src, $w) !== false) return false;
    }
    return true;
});
t('T21', 'CRUD tetap jalan dengan InternationalStudent (update & delete)', function () {
    $repo = new StudentRepository();
    $repo->create(new InternationalStudent('M03','Chen','TI',5000000));
    $repo->update('M03', new RegularStudent('M03','Chen','TI',4000000)); // ganti jenis, NIM sama
    $ok1 = $repo->find('M03') instanceof RegularStudent;
    $repo->delete('M03');
    return $ok1 && $repo->find('M03') === null;
});

// --- Tugas 4: aturan overriding (proses terpisah) ---
t('T22', 'signature tidak kompatibel -> Fatal error', function () {
    $o = runExperiment('exp_signature_incompatible.php');
    return stripos($o, 'must be compatible') !== false && stripos($o, 'tidak akan tercapai') === false;
});
t('T23', 'visibility lebih ketat -> Fatal error', function () {
    $o = runExperiment('exp_visibility_stricter.php');
    return stripos($o, 'Access level') !== false && stripos($o, 'tidak akan tercapai') === false;
});
t('T24', 'method private parent bukan override (valid)', function () {
    $o = runExperiment('exp_private_parent.php');
    return str_contains($o, 'parent-secret') && str_contains($o, 'child-secret');
});
t('T25', 'Student::getId() final (Reflection) & aplikasi utama tetap valid', fn() => (new ReflectionMethod(Student::class, 'getId'))->isFinal());
t('T26', 'deklarasi add() ganda (gaya Java/C++) -> Fatal error', function () {
    $o = runExperiment('exp_redeclare_method.php');
    return stripos($o, 'Cannot redeclare') !== false;
});

$fail = count(array_filter($results, fn($r) => !$r[2]));
echo "\nTOTAL: ".(count($results) - $fail).' lulus, '.$fail.' gagal dari '.count($results)." tes\n";
exit($fail > 0 ? 1 : 0);
