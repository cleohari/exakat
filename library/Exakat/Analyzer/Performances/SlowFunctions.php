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


namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Common\FunctionUsage;

class SlowFunctions extends FunctionUsage {
    public function analyze(): void {
        $this->functions = array(
'array_diff',
'array_intersect',
'array_key_exists',
'array_map',
'array_search',
'array_udiff',
'array_uintersect',
'array_unshift',
'array_walk',
'in_array',
'preg_replace',
'strstr',
'uasort',
'uksort',
'usort',
);

        // array_unique was upgraded in PHP 7.2
        if (version_compare('7.2', $this->config->phpversion) > 1) {
            $this->functions[] = 'array_unique';
        }

        parent::analyze();
    }
}

?>
