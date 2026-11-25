<?php
declare(strict_types=1);

/**
 * Mahasiswa internasional.
 *
 * ASUMSI (bahan praktikum tidak menentukan rumus biaya InternationalStudent):
 *   biaya = baseFee x (1 + SURCHARGE_PERCENT / 100) + SERVICE_FEE
 * Kedua konstanta di bawah ini sengaja dibuat mudah diubah.
 * Contoh: baseFee Rp5.000.000 -> 5.000.000 x 1,5 + 1.000.000 = Rp8.500.000.
 */
final class InternationalStudent extends Student {
    public const SURCHARGE_PERCENT = 50.0;     // tambahan biaya kuliah (persen dari biaya dasar)
    public const SERVICE_FEE       = 1000000.0; // biaya layanan tetap (rupiah)

    public function getType(): string { return 'international'; }

    // overriding abstract method calculateFee() dengan aturan biaya berbeda
    public function calculateFee(): float {
        return $this->baseFee * (1 + self::SURCHARGE_PERCENT / 100) + self::SERVICE_FEE;
    }

    // overriding summary() sambil memakai implementasi parent
    public function summary(): string {
        return parent::summary() . ' [Internasional]';
    }
}
