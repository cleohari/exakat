<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Interfaces;

use Exakat\Analyzer\Analyzer;

class CouldUseInterface extends Analyzer {
    // interface i {}
    // class x { function i() {}}
    // class x could use interface i
    public function analyze() {
        $query = <<<GREMLIN
g.V().hasLabel("Interface")
     .as('name')
     .out("METHOD").out("NAME").as("method")
     .select('name', 'method').by('fullnspath').by('code');
GREMLIN;

        $res = $this->query($query)->toArray();

        if (empty($res)) {
            return;
        }
        
        $interfaces = array();
        foreach($res as $row) {
            if (isset($interfaces[$row['name']])) {
                $interfaces[$row['name']][] = $row['method'];
            } else {
                $interfaces[$row['name']] = array($row['method']);
            }
        }

        $this->atomIs('Class')
             ->hasOut(array('METHOD', 'MAGICMETHOD'))
             ->raw('sideEffect{ x = []; }')
             ->raw('sideEffect{ i = *** }', $interfaces)
             ->raw('where( __.out("METHOD", "MAGICMETHOD").out("NAME").sideEffect{ x.add(it.get().value("code")) ; }.fold() )')
             ->raw('filter{ 
                            a = false;
                            i.each{ n, e ->
                                if (x.intersect(e) == e) {
                                    a = true;
                                    fnp = n;
                                }
                            }
                            
                            a;
                        }')
                ->raw('not( where( __.repeat( __.out("IMPLEMENTS", "EXTENDS").in("DEFINITION") ).emit().times(15)
                                     .hasLabel("Interface", "Class")
                                     .filter{ it.get().value("fullnspath") == fnp; } ) )')
                ->back('first');
        $this->prepareQuery();
    }
}

?>
