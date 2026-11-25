<?php
// EKSPERIMEN SENGAJA ERROR - jangan di-require dari aplikasi utama.
// Meniru overloading tradisional Java/C++ di PHP.
// Hasil yang diharapkan: Fatal error: Cannot redeclare Calculator2::add()
class Calculator2 {
    public function add(int $a, int $b): int { return $a + $b; }
    public function add(int $a, int $b, int $c): int { return $a + $b + $c; }
}
echo "Baris ini tidak akan tercapai.\n";
