<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NullableWithConstant extends Analyzer {
    /* 1 methods */

    public function testFunctions_NullableWithConstant01()  { $this->generic_test('Functions/NullableWithConstant.01'); }
}
?>