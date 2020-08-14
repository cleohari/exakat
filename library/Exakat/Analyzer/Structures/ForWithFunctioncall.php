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

class ForWithFunctioncall extends Analyzer {
    public function analyze(): void {
        //for(; $b < 10; $a++)
        $this->atomIs('For')
             ->analyzerIsNot('self')
            // This looks for variables inside the INCREMENT
             ->outIs('INCREMENT')
             ->collectVariables('variables')
             ->back('first')
             ->outIs('FINAL')
             ->atomInsideNoDefinition('Functioncall')
            // This checks for usage of increment variables inside the FINAL
             ->noCodeInside('Variable', 'variables')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             ->analyzerIsNot('self')
             ->outIs('INCREMENT')
             ->atomInsideNoDefinition('Functioncall')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
