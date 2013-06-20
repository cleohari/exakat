<?php

namespace Tokenizer;

class Comparison extends TokenAuto {
    function _check() {
    
        $operands = array('Variable', 'Array', 'Property', 'Integer', 'Sign', 'Float', 'Constant', 'Boolean',
                          'Property', 'Staticproperty', 'Methodcall', 'Staticmethodcall', 'Functioncall' );
        $operators = array('==','!=', '>=', '<=', '===', '!==', '>', '<',  );
        
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR')), 
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => $operators,
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'RIGHT',
                                                      '-1' => 'LEFT'
                                                      ),
                               'atom'       => 'Comparison',
                               );
//        $this->printQuery();
        $r = $this->checkAuto(); 

        return $r;
    }

    
    function reserve() {
        Token::$reserved[] = '==';
        Token::$reserved[] = '!=';
        Token::$reserved[] = '>=';
        Token::$reserved[] = '<=';
        Token::$reserved[] = 'T_IS_NOT_IDENTICAL';
        Token::$reserved[] = 'T_IS_IDENTICAL';

        return true;
    }
}

?>