<?php
// EKSPERIMEN VALID (tidak error): method private parent BUKAN method warisan,
// sehingga method bernama sama di child bukan overriding.
// Hasil yang diharapkan:
//   Parent::run() memanggil -> parent-secret
//   Child::secret() langsung -> child-secret
declare(strict_types=1);

class ParentClass {
    private function secret(): string { return 'parent-secret'; }
    public function run(): string { return $this->secret(); } // selalu memakai secret() milik ParentClass
}
class ChildClass extends ParentClass {
    public function secret(): string { return 'child-secret'; } // method BARU, bukan override
}

$c = new ChildClass();
echo 'Parent::run() memanggil -> ' . $c->run() . PHP_EOL;
echo 'Child::secret() langsung -> ' . $c->secret() . PHP_EOL;
