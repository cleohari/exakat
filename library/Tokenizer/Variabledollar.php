<?php

namespace Tokenizer;

class Variabledollar extends TokenAuto {
    static public $operators = array('T_DOLLAR');
    
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_DOLLAR',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array('T_OPEN_BRACKET')),
        );
        
        $this->actions = array( 'transform' => array('1' => 'NAME'),
                                'atom'      => 'Variable');
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => 'T_DOLLAR',
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_OPEN_CURLY'),
                                  2 => array('atom' => 'yes'),
                                  3 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array(1 => 'DROP',
                                                     2 => 'NAME',
                                                     3 => 'DROP'),
                                'atom'      => 'Variable');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>