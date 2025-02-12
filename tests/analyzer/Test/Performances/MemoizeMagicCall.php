<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MemoizeMagicCall extends Analyzer {
    /* 3 methods */

    public function testPerformances_MemoizeMagicCall01()  { $this->generic_test('Performances/MemoizeMagicCall.01'); }
    public function testPerformances_MemoizeMagicCall02()  { $this->generic_test('Performances/MemoizeMagicCall.02'); }
    public function testPerformances_MemoizeMagicCall03()  { $this->generic_test('Performances/MemoizeMagicCall.03'); }
}
?>