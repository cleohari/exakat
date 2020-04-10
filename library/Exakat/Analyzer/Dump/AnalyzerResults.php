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

namespace Exakat\Analyzer\Dump;

use Exakat\Analyzer\Dump\AnalyzerDump;
use Exakat\Dump\Dump;

abstract class AnalyzerResults extends AnalyzerDump {
    protected $storageType = self::QUERY_RESULTS;

    public function prepareQuery() : void {
        ++$this->queryId;

        $result = $this->rawQuery();

        ++$this->queryCount;

        $c = $result->toArray();
        if (!is_array($c) || !isset($c[0])) {
            return ;
        }

        $this->processedCount += count($c);
        $this->rowCount       += count($c);

        $valuesSQL = array();
        foreach($c as $row) {
            $row = array_map(array('\\Sqlite3', 'escapeString'), $row);
            $row['analyzer']  = $this->shortAnalyzer;
            $valuesSQL[] = "(NULL, '".implode("', '", $row)."', 0) \n";
        }

        $chunks = array_chunk($valuesSQL, 490);
        foreach($chunks as $chunk) {
            $query = 'INSERT INTO '.$this->analyzerTable.' VALUES ' . implode(', ', $chunk);
            $this->dumpQueries[] = $query;
        }

    }
    
    public function execQuery() : int {
        array_unshift($this->dumpQueries, "DELETE FROM results WHERE analyzer = '{$this->analyzerName}'");

        if (count($this->dumpQueries) >= 1) {
            $this->prepareForDump($this->dumpQueries);
        }

        $this->dumpQueries = array();
        
        return 0;
    }

    public function getDump(): array {
        $dump      = Dump::factory($this->config->dump);
    
        $res = $dump->fetchAnalysers(array($this->shortAnalyzer));
        return $res->toArray();
    }
}

?>
