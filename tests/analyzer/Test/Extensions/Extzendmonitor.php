<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extzendmonitor extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extzendmonitor01()  { $this->generic_test('Extensions/Extzendmonitor.01'); }
}
?>