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

namespace Exakat\Analyzer\Complete;

class MakeClassMethodDefinition extends Complete {
    public function dependsOn(): array {
        return array('Complete/SetParentDefinition',
                     'Complete/CreateDefaultValues',
                    );
    }

    public function analyze() {

        // Warning : no support for overwritten methods : ALL methods are linked

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs(self::CLASSES_TRAITS, self::WITHOUT_CONSTANTS)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs('Static', self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs(self::CLASSES_TRAITS,  self::WITHOUT_CONSTANTS)
              ->goToAllChildren(self::EXCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between Class method and definition
        // This works only for $this
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('This', self::WITHOUT_CONSTANTS)
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->goToInstruction(self::CLASSES_TRAITS)
              ->goToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between Class method and definition
        // This works only for $this
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('This', self::WITHOUT_CONSTANTS)
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->goToInstruction(self::CLASSES_TRAITS)
              ->goToAllParents(self::INCLUDE_SELF)

              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs(array('As', 'Insteadof'), self::WITHOUT_CONSTANTS)
              ->outIs(array('AS', 'INSTEADOF'))
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs(array('AS', 'INSTEADOF'))
              ->outIs('NAME')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'realname')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Trait', self::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between Class method and definition
        // This works only for $this
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('This', self::WITHOUT_CONSTANTS)
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->goToInstruction(self::CLASSES_TRAITS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->as('theClass')

              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs('As', self::WITHOUT_CONSTANTS)
              ->outIs('AS')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('AS')
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'realname')
              ->back('theClass')

              ->outIs('USE')
              ->outIs('USE')
              ->inIs('DEFINITION')
              ->atomIs('Trait', self::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between Class method and definition
        // This works only for $this
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->goToAllParents(self::INCLUDE_SELF)

              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs(array('As', 'Insteadof'), self::WITHOUT_CONSTANTS)
              ->outIs(array('AS', 'INSTEADOF'))
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs(array('AS', 'INSTEADOF'))
              ->outIs('NAME')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'realname')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Trait', self::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between Class method and definition
        // This works only for $this
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->goToAllParents(self::INCLUDE_SELF)
              ->as('theClass')

              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs('As', self::WITHOUT_CONSTANTS)
              ->outIs('AS')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('AS')
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'realname')
              ->back('theClass')

              ->outIs('USE')
              ->outIs('USE')
              ->inIs('DEFINITION')
              ->atomIs('Trait', self::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))

              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous'), self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::EXCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->isNot('visibility', 'private')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs('Parent', self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->GoToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->isNot('visibility', 'private')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between constructor and new call
        $this->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->atomIs(array('Newcall', 'Self', 'Parent'), self::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Clasanonymous'), self::WITHOUT_CONSTANTS)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__construct', self::TRANSLATE, self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        $this->atomIs('New', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('NEW')
              ->atomIs(array('Newcall', 'Self', 'Parent'), self::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Clasanonymous'), self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'name')
              ->inIs('NAME')
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        $this->atomIs('New', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('NEW')
              ->atomIs(array('Newcall', 'Self', 'Parent'), self::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Clasanonymous'), self::WITHOUT_CONSTANTS)
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->raw(<<<'GREMLIN'
until( __.out("MAGICMETHOD").out("NAME").has("fullcode", "__construct")).repeat( __.out("EXTENDS").in("DEFINITION"))
GREMLIN
)
              ->outIs('MAGICMETHOD')
              ->codeIs('__construct', self::TRANSLATE, self::CASE_INSENSITIVE)
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // Create link between __clone and clone
        // parenthesis, typehint, local new,
        $this->atomIs('Clone', self::WITHOUT_CONSTANTS)
              ->outIs('CLONE')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->atomIs(self::CLASSES_TRAITS, self::WITHOUT_CONSTANTS)
              ->outIs('MAGICMETHOD')
              ->codeIs('__clone', self::TRANSLATE, self::CASE_INSENSITIVE)
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();
    }
}

?>
