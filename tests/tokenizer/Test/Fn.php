<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Fn extends Tokenizer {
    /* 2 methods */

    public function testFn01()  { $this->generic_test('Fn.01'); }
    public function testFn02()  { $this->generic_test('Fn.02'); }
}
?>