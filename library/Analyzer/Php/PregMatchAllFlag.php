<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/
namespace Analyzer\Php;

use Analyzer;

class PregMatchAllFlag extends Analyzer\Analyzer {
    public function analyze() {
        // Using default configuration
        $this->atomFunctionIs('\preg_match_all')
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', 3)
             ->outWithRank('ARGUMENT', 2)
             ->savePropertyAs('code', 'r')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->nextSiblings() // Do we really need all of them? May be limit to 3/5
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('SOURCE')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('KEY')
             ->savePropertyAs('code', 'k')
             ->inIs('KEY')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Array')
             ->outIs('VARIABLE')// $r[1][$id]
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->samePropertyAs('code', 'k')
             ->back('first');
        $this->prepareQuery();

        // Using explicit configuration
        $this->atomFunctionIs('\preg_match_all')
             ->outIs('ARGUMENTS')
             ->outWithRank('ARGUMENT', 3)
             ->fullnspathIs('\preg_pattern_order')
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 2)
             ->savePropertyAs('code', 'r')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->nextSiblings() // Do we really need all of them? May be limit to 3/5
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('SOURCE')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('KEY')
             ->savePropertyAs('code', 'k')
             ->inIs('KEY')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Array')
             ->outIs('VARIABLE')// $r[1][$id]
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->samePropertyAs('code', 'k')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
