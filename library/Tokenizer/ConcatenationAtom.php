<?php

namespace Tokenizer;

class ConcatenationAtom extends TokenAuto {
    function _check() {
// Fusion of 2 concatenations
        $this->conditions = array( -1 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_COMMA')), 
                                   0 => array('atom' => array('String', 'Variable', 'Property', 'Array', 'Concatenation')),
                                   1 => array('atom' => array('String', 'Variable', 'Property', 'Array', 'Concatenation')),
                                   2 => array('filterOut' => array_merge(Assignation::$operators, array('T_CLOSE_PARENTHESIS', 'T_COMMA')) ),
        ); 
        $this->actions = array('mergeConcat' => "Concat");
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>