<?php
// EKSPERIMEN SENGAJA ERROR - jangan di-require dari aplikasi utama.
// Return type calculateFee() diubah dari float menjadi string. Hasil yang diharapkan:
// Fatal error: Declaration of BadSignature::calculateFee(): string must be
// compatible with Student::calculateFee(): float
declare(strict_types=1);
require __DIR__.'/_base.php';

class BadSignature extends Student {
    public function getType(): string { return 'exp'; }
    public function calculateFee(): string { return 'gratis'; }
}
echo "Baris ini tidak akan tercapai.\n";
