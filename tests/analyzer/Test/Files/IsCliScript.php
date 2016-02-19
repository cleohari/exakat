<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Files_IsCliScript extends Analyzer {
    /* 4 methods */

    public function testFiles_IsCliScript01()  { $this->generic_test('Files/IsCliScript.01'); }
    public function testFiles_IsCliScript02()  { $this->generic_test('Files/IsCliScript.02'); }
    public function testFiles_IsCliScript03()  { $this->generic_test('Files/IsCliScript.03'); }
    public function testFiles_IsCliScript04()  { $this->generic_test('Files/IsCliScript.04'); }
}
?>