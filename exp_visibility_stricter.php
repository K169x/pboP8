<?php
// EKSPERIMEN SENGAJA ERROR - jangan di-require dari aplikasi utama.
// Visibility di child (protected) lebih ketat daripada parent (public). Hasil yang diharapkan:
// Fatal error: Access level to BadVisibility::calculateFee() must be public (as in class Student)
declare(strict_types=1);
require __DIR__.'/_base.php';

class BadVisibility extends Student {
    public function getType(): string { return 'exp'; }
    protected function calculateFee(): float { return 0.0; }
}
echo "Baris ini tidak akan tercapai.\n";
