<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ContinueIsForLoop extends Analyzer {
    public function analyze(): void {
        $switchAndLoop = array('Switch', 'Foreach', 'For', 'While', 'Dowhile');

        // foreach() { switch () { case 1 : continue; } }
        $this->atomIs('Continue')
             ->outIs('CONTINUE')
             ->is('intval', array(0, 1))
             ->back('first')
             ->goToInstruction($switchAndLoop)
             ->atomIs('Switch');
        $this->prepareQuery();

        // foreach() { switch () { case 1 : continue; } }
        $this->atomIs('Continue')
             ->outIs('CONTINUE')
             ->is('intval', 2)
             ->back('first')
             ->goToInstruction($switchAndLoop)
             ->goToInstruction($switchAndLoop)
             ->atomIs('Switch');
        $this->prepareQuery();

        // foreach() { switch () { case 1 : continue; } }
        $this->atomIs('Continue')
             ->outIs('CONTINUE')
             ->is('intval', 3)
             ->back('first')
             ->goToInstruction($switchAndLoop)
             ->goToInstruction($switchAndLoop)
             ->goToInstruction($switchAndLoop)
             ->atomIs('Switch');
        $this->prepareQuery();
    }
}

?>
