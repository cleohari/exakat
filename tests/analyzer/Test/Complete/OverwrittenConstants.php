<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OverwrittenConstants extends Analyzer {
    /* 1 methods */

    public function testComplete_OverwrittenConstants01()  { $this->generic_test('Complete/OverwrittenConstants.01'); }
}
?>