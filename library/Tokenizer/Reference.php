<?php

namespace Tokenizer;

class Reference extends TokenAuto {
    static public $operators = array('T_AND');

    function _check() {
        $this->conditions = array(-1 => array('filterOut2' => array_merge(Logical::$operators, 
                                                                array('T_VARIABLE', 'T_LNUMBER', 'T_DNUMBER', 'T_STRING',
                                                                      'T_MINUS', 'T_PLUS', 'T_CLOSE_PARENTHESIS', 
                                                                      'T_CLOSE_BRACKET', 'T_CLOSE_PARENTHESIS' )),
                                              'notAtom'    => array('Parenthesis', 'Array', 'Comparison', 'Bitshift', )),
                                   0 => array('token' => Reference::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => array('Variable', 'Array', 'Property', 'Functioncall', 'Methodcall', 'Staticmethodcall', 
                                                              'Staticproperty', 'Staticconstant', 'New', 'Arrayappend', )),
                                   2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', )),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'REFERENCE'),
                               'atom'        => 'Reference',
                               'cleanIndex'  => true);
        $this->checkAuto();

        // special case for Stdclass &$x = 
        $this->conditions = array(-2 => array('filterOut' => 'T_DOUBLE_COLON'),
                                  -1 => array('token' => 'T_STRING'), 
                                   0 => array('token' => Reference::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => array('Variable')),
                                   2 => array('token' => array('T_COMMA', 'T_EQUAL', 'T_CLOSE_PARENTHESIS')),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'REFERENCE'),
                               'atom'        => 'Reference',
                               'cleanIndex'  => true);
        $this->checkAuto();

        $this->conditions = array(-1 => array('token' => 'T_FUNCTION',
                                             'atom' => 'none'),
                                  0 => array('token' => Reference::$operators),
                                  1 => array('atom' => 'Identifier'),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  3 => array('atom' => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'   => array( 1 => 'REFERENCE'),
                               'cleanIndex'  => true,
                               'atom'        => 'Reference');
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>