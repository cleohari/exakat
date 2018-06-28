<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class CookiesVariables extends Analyzer {
    public function analyze() {
        $cookie = $this->dictCode->translate(array('$_COOKIE'));
        
        if (!empty($cookie)) {
            // $_COOKIE['name'];
            $this->atomIs('Phpvariable')
                 ->codeIs($cookie, self::NO_TRANSLATE, self::CASE_SENSITIVE)
                 ->inIs('VARIABLE')
                 ->atomIs('Array')
                 ->outIs('INDEX')
                 ->atomIs('String');
            $this->prepareQuery();
        }

        // setcookie($name, ...);
        $this->atomFunctionIs('\\setcookie')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String');
        $this->prepareQuery();
    }
}

?>
