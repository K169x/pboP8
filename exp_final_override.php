<?php
// EKSPERIMEN SENGAJA ERROR - jangan di-require dari aplikasi utama.
// Student::getId() adalah method final. Hasil yang diharapkan:
// Fatal error: ... cannot override final method Student::getId()
declare(strict_types=1);
require __DIR__.'/_base.php';

class BadFinal extends RegularStudent_Base {
    public function getId(): string { return 'HACK'; }
}
echo "Baris ini tidak akan tercapai.\n";
