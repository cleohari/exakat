<?php

namespace Analyzer\Variables;

use Analyzer;

class IsRead extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\Constructor');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->hasIn(array('NOT', 'AT', 'OBJECT', 'NEW', 'RETURN', 'CONCAT', 'SOURCE', 'CODE', 'INDEX', 'CONDITION', 'THEN', 'ELSE',
                           'KEY', 'VALUE', 'NAME', 'DEFINE', 'PROPERTY', 'METHOD', 'VARIABLE', 'SIGN', 'THROW', 'CAST' ));
            // note : NAME is for Switch!!
        $this->prepareQuery();

        // $this is always read
        $this->atomIs("Variable")
             ->code('$this');
        $this->prepareQuery();
             

        // right or left, same 
        $this->atomIs("Variable")
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift'))
             ->back('first');
        $this->prepareQuery();

        // right only
        $this->atomIs("Variable")
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->back('first');
        $this->prepareQuery();

        // $x++ + 2 (a plusplus within another 
        $this->atomIs("Variable")
             ->inIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift'))
             ->back('first');
        $this->prepareQuery();

        // variable in a sequence (also useless...)
        $this->atomIs("Variable")
             ->inIs('ELEMENT')
             ->atomIs('Sequence')
             ->back('first');
        $this->prepareQuery();    

        // array only
        $this->atomIs("Variable")
             ->inIs('VARIABLE')
             ->atomIs(array('Array', 'Arrayappend'))
             ->back('first');
        $this->prepareQuery();

        // arguments : Typehint
        $this->atomIs("Variable")
             ->inIs('VARIABLE')
             ->atomIs(array('Typehint', 'Instanceof'))
             ->back('first');
        $this->prepareQuery();    

        // arguments : normal variable in a custom function
        $this->atomIs("Variable")
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoIn('METHOD') // possibly new too
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', true)
             ->isNot('reference', 'true')
             ->back('first');
        $this->prepareQuery();  

        // PHP functions that are passed by value
        $data = new \Data\Methods();
        
        $functions = $data->getFunctionsValueArgs();
        $references = array();
        
        foreach($functions as $function) {
            if (!isset($references[$function['position']])) {
                $references[$function['position']] = array('\\'.$function['function']);
            } else {
                $references[$function['position']][] = '\\'.$function['function'];
            }
        }
        
        foreach($references as $position => $functions) {
            $this->atomIs("Variable")
                 ->is('rank', $position)
                 ->inIs('ARGUMENT')
                 ->inIs('ARGUMENTS')
                 ->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->fullnspath($functions)
                 ->back('first');
            $this->prepareQuery();
        }

        $this->atomIs("Variable")
             ->hasIn(array('ARGUMENT'))
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").has("atom", "Function").any() == false}')
             ->analyzerIsNot('Analyzer\\\\Variables\\\\IsRead');
        $this->prepareQuery();

        // Class constructors (__construct)
        $this->atomIs("Variable")
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Functioncall')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->_as('method')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->back('method')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', true)
             ->isNot('reference', 'true')
             ->back('first');
        $this->prepareQuery();

        // Class constructors with self
        $this->atomIs("Variable")
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Functioncall')
             ->code('self')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->_as('method')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->back('method')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', true)
             ->isNot('reference', 'true')
             ->back('first');
        $this->prepareQuery();
    }
}

?>