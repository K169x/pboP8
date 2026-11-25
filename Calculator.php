<?php
declare(strict_types=1);

/** Variadic: SATU deklarasi add(), jumlah argumen bervariasi karena operator "...". */
class Calculator {
    public function add(int ...$numbers): int {
        return array_sum($numbers);
    }
}
