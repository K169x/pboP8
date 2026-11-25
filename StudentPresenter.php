<?php
declare(strict_types=1);
/**
 * PHP overloading: __call() menangani method yang TIDAK dideklarasikan.
 *
 * showData() sengaja tidak dideklarasikan. Pola yang diterima:
 *   showData(name)             -> 1 string
 *   showData(id, name)         -> 2 string
 *   showData(id, name, major)  -> 3 string  (Tugas 2 Pertemuan 8)
 * Selain itu ditolak dengan exception yang jelas.
 */
final class StudentPresenter {
    public function __call(string $method, array $args): string {
        if ($method !== 'showData') {
            throw new BadMethodCallException("Method tidak dikenal: $method");
        }

        // named argument (showData(name: 'x')) masuk ke $args dengan key string -> tolak
        if (array_values($args) !== $args) {
            throw new InvalidArgumentException('Named argument ditolak.');
        }

        $count = count($args);
        if ($count < 1 || $count > 3) {
            throw new InvalidArgumentException(
                "showData menerima 1-3 argumen string, menerima $count"
            );
        }

        foreach ($args as $i => $arg) {
            if (!is_string($arg)) {
                $n = $i + 1; $t = get_debug_type($arg);
                throw new InvalidArgumentException(
                    "Argumen ke-$n harus string, bertipe $t"
                );
            }
        }

        return match ($count) {
            1 => 'Nama: ' . $args[0],
            2 => 'NIM: ' . $args[0] . ', Nama: ' . $args[1],
            3 => 'NIM: ' . $args[0] . ', Nama: ' . $args[1]
                 . ', Prodi: ' . $args[2],
        };
    }
}
