<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extwincache extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extwincache01()  { $this->generic_test('Extensions_Extwincache.01'); }
}
?>