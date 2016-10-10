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

namespace Analyzer\Classes;

use Analyzer;

class MultipleTraitOrInterface extends Analyzer\Analyzer {
    public function analyze() {
        // interfaces
        $this->atomIs('Class')
             ->raw(<<<'GREMLIN'
where( __.sideEffect{counts = [:]}
         .out("IMPLEMENTS")
         .sideEffect{ k = it.get().value("fullnspath"); 
                    if (counts[k] == null) {
                       counts[k] = 1;
                    } else {
                       counts[k]++;
                    }
         }.fold()
       )
       .sideEffect{ names = counts.findAll{ a,b -> b > 1}.keySet() }
       .out("IMPLEMENTS")
       .filter{ it.get().value("fullnspath") in names }
GREMLIN
)
      ->back('first')
      ->analyzerIsNot('self');
      $this->prepareQuery();

        // traits
        $this->atomIs('Class')
             ->raw(<<<'GREMLIN'
where( __.sideEffect{counts = [:]}
         .out("BLOCK").out("ELEMENT").hasLabel("Use").out("USE")
         .sideEffect{ k = it.get().value("fullnspath"); 
                    if (counts[k] == null) {
                       counts[k] = 1;
                    } else {
                       counts[k]++;
                    }
         }.fold()
       )
       .sideEffect{ names = counts.findAll{ a,b -> b > 1}.keySet() }
       .out("BLOCK").out("ELEMENT").hasLabel("Use").out("USE")
       .filter{ it.get().value("fullnspath") in names }
GREMLIN
)
      ->back('first')
      ->analyzerIsNot('self');
      $this->prepareQuery();
    }
}

?>
