<?php

namespace Report\Template;

class Table extends \Report\Template {
    private $hash = array('Empty' => 'hash');
    private $summary = false;

    private $headersName = array();
    
    private $countedValues = false;
    
    const SORT_NONE = 1;
    const SORT_COUNT = 2;
    const SORT_REV_COUNT = 3;
    const SORT_KEY = 4;
    const SORT_REV_KEY = 4;
    
    public function render($output) {
        $data = $this->data->toArray();
        
        $renderer = $output->getRenderer('Table');
        $renderer->render($output, $data);
    }
    
    public function setContent($data) {
        if ($data === null) {
            $this->data = $data; 
        } 

        if (empty($this->headersName)) {
            $a = $data->toArray();
            $this->headersName = array_fill(0, count($a[0]), '');
        }
    }

    public function setSort($sort) {
        if (in_array($sort, range(1, 5))) {
            $this->sort = $sort; 
        }
    }

    public function setSummary($summary) {
        $this->summary = (bool) $summary;
    }

    public function setHeadersNames($name) {
        $this->headersName = $name; 
    }
}

?>