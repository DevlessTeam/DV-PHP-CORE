<?php

namespace Devless\RulesEngine;

trait flowControl
{
    /**
     * equivalence of if.
     *
     * @param  $assert
     *
     * @return $this
     */
    public function whenever($assert)
    {
        if (!$this->isCurrentDBAction) {
            return $this;
        }
        $this->assertion['whenever'] = $assert;
        $this->called['whenever'] = true;
        $this->execOrNot = ($assert);

        return $this;
    }
    /**
     * equivalence of elseif.
     *
     * @param  $assert
     *
     * @return $this
     */
    public function elseWhenever($assert)
    {
        if(!$this->isCurrentDBAction){return $this;}   
        if($this->assertion['whenever'] || $this->assertion['elseWhenever']) {
            $this->execOrNot = false;
            return $this;
        }
        
        $this->assertion['elseWhenever'] = $assert;
        $this->called['elseWhenever'] = true;
        $this->execOrNot = ($assert);

        return $this;
    }
    /**
     * equivalence of else.
     *
     * @return $this
     */
    public function otherwise()
    {
        if(!$this->isCurrentDBAction){return $this;}   
        if($this->assertion['whenever'] || $this->assertion['elseWhenever']) {
            $this->execOrNot = false;
            return $this;
        }  
        
        $this->called['otherwise'] = true;
        $this->execOrNot = true;

        return $this;
    }
}
