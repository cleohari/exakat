<?php

$expected     = array('fooBoolean( ) === true',
                      'fooInt( ) === 1',
                     );

$expected_not = array('fooBoolean( ) === 1',
                      'fooInt( ) === true',
                      'fooBoolean( ) == true',
                      'fooInt( ) == 1',
                     );

?>