<?php
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
declare(strict_types = 1);

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class HardcodedPasswords extends Analyzer {
    protected $passwordsKeys = '';

    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        // Position is 0 based
        $passwordsFunctions = $this->loadJson('php_logins.json');

        $functions = (array) $passwordsFunctions->functions;
        $positions = array_groupby( $functions);

        foreach($positions as $position => $function) {
            $function = makeFullNsPath($function);
            $this->atomFunctionIs($function)
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
                 ->back('first');
            $this->prepareQuery();
        }

        // ['password' => "1"];
        $this->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->as('value')
             ->outIs('INDEX')
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->noDelimiterIs($this->passwordsKeys, self::CASE_SENSITIVE)
             ->back('value')
             ->outIs('VALUE')
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->regexIsNot('noDelimiter', 'required')
             ->back('first');
        $this->prepareQuery();

        // $a['password'] = "1";
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->noDelimiterIs($this->passwordsKeys, self::CASE_SENSITIVE)
             ->back('first')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->regexIsNot('noDelimiter', 'required')
             ->back('first');
        $this->prepareQuery();

        // $a->password = 'abc';
        $this->atomIs('Member')
             ->hasIn('LEFT')
             ->outIs('MEMBER')
             ->codeIs($this->passwordsKeys)
             ->back('first')

             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->regexIsNot('noDelimiter', 'required')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
