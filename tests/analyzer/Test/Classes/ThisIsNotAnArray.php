<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_ThisIsNotAnArray extends Analyzer {
    /* 6 methods */

    public function testClasses_ThisIsNotAnArray01()  { $this->generic_test('Classes_ThisIsNotAnArray.01'); }
    public function testClasses_ThisIsNotAnArray02()  { $this->generic_test('Classes_ThisIsNotAnArray.02'); }
    public function testClasses_ThisIsNotAnArray03()  { $this->generic_test('Classes_ThisIsNotAnArray.03'); }
    public function testClasses_ThisIsNotAnArray04()  { $this->generic_test('Classes/ThisIsNotAnArray.04'); }
    public function testClasses_ThisIsNotAnArray05()  { $this->generic_test('Classes/ThisIsNotAnArray.05'); }
    public function testClasses_ThisIsNotAnArray06()  { $this->generic_test('Classes/ThisIsNotAnArray.06'); }
}
?>