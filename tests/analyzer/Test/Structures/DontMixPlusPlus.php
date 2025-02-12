<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontMixPlusPlus extends Analyzer {
    /* 3 methods */

    public function testStructures_DontMixPlusPlus01()  { $this->generic_test('Structures/DontMixPlusPlus.01'); }
    public function testStructures_DontMixPlusPlus02()  { $this->generic_test('Structures/DontMixPlusPlus.02'); }
    public function testStructures_DontMixPlusPlus03()  { $this->generic_test('Structures/DontMixPlusPlus.03'); }
}
?>