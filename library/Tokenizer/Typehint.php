<?php

namespace Tokenizer;

class Typehint extends TokenAuto {
    static public $operators = array('T_COMMA', 'T_OPEN_PARENTHESIS');
    static public $atom = 'Typehint';
    
    public function _check() {
        // norml case for classes
        $this->conditions = array(-1 => array('filterOut' => 'T_CATCH'),
                                   0 => array('token'     => Typehint::$operators),
                                   1 => array('atom'      => 'yes', 
                                              'token'     => array('T_STRING', 'T_NS_SEPARATOR')),
                                   2 => array('atom'      => array('Variable', 'Assignation', 'Identifier' )),
                                   3 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('to_typehint'  => true,
                               'keepIndexed'  => true);
        $this->checkAuto();

        // special case for arrays
        $this->conditions = array(-2 => array('filterOut' => 'T_CATCH'),
                                   0 => array('token' => Typehint::$operators),
                                   1 => array('token' => 'T_ARRAY', 
                                              'atom' => 'none'),
                                   2 => array('atom' => array('Variable', 'Assignation', 'Identifier'    )),
                                   3 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('to_typehint'  => true,
                               'keepIndexed'  => true);
        $this->checkAuto();

        // special case for callable
        $this->conditions = array(-2 => array('filterOut' => 'T_CATCH'),
                                   0 => array('token' => Typehint::$operators),
                                   1 => array('token' => 'T_CALLABLE'),
                                   2 => array('atom' => array('Variable', 'Assignation', 'Identifier'    )),
                                   3 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('to_typehint'  => true,
                               'keepIndexed'  => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("CLASS").next().getProperty("fullcode") + " " + fullcode.out("VARIABLE").next().getProperty('fullcode')); 

GREMLIN;
    }
}

?>