<?php
// Helper untuk eksperimen: subclass Student yang valid dan tidak final.
declare(strict_types=1);
require_once __DIR__.'/../src/bootstrap.php';

class RegularStudent_Base extends Student {
    public function getType(): string { return 'exp'; }
    public function calculateFee(): float { return $this->baseFee; }
}
