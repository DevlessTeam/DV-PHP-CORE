<?php

namespace Devless\RulesEngine;

trait flowControl
{
    /**
     * This is the equivalence of if and is mostly used together with assertions to alter execution flows. eg beforeQuerying()->whenever(assertIts::equal($input_name, "edmond"))->then->succeedWith("yes the names are same")
     *
     * @param $assertion
     *
     * @return $this
     */
    public function whenever($assert)
    {
        if (!$this->isCurrentDBAction) {
            return $this;
        }
        if($this->stopAndOutputCalled) {return $this;
        }
        $this->assertion['whenever'] = ($assert);
        $this->called['whenever'] = true;
        $this->execOrNot = ($assert);

        return $this;
    }
    /**
     * This is the equivalence of elseif and is mostly used together with assertions to alter execution flows eg.beforeQuerying()->whenever(assertIts::equal($input_name, "edmond"))->then->succeedWith("yes the names are same")->elseWhenever(assertIts::equal($input_name, "charles")))->then->succeedWith("yes the name is charles")
     *
     * @param $assertion
     *
     * @return $this
     */
    public function elseWhenever($assert)
    {
        if(!$this->isCurrentDBAction) {return $this;
        }   
        if($this->assertion['whenever'] || $this->assertion['elseWhenever']) {
            $this->execOrNot = false;
            return $this;
        }
        
        $this->assertion['elseWhenever'] = ($assert);
        $this->called['elseWhenever'] = true;
        $this->execOrNot = ($assert);

        return $this;
    }
    /**
     * This is the equivalence of else eg.beforeQuerying()->whenever(assertIts::equal($input_name, "edmond"))->then->succeedWith("yes the names are same")->elseWhenever(assertIts::equal($input_name, "charles")))->then->succeedWith("yes the names are charles")->otherwise()->succeedWith("Its some other name")
     *
     * @return $this
     */
    public function otherwise()
    {
        if(!$this->isCurrentDBAction) {return $this;
        }   
        if($this->assertion['whenever'] || $this->assertion['elseWhenever']) {
            $this->execOrNot = false;
            return $this;
        }  
        
        $this->called['otherwise'] = true;
        $this->execOrNot = true;

        return $this;
    }

    /**
     * This is the equivalence of endIf eg.beforeQuerying()->whenever(assertIts::equal($input_name, "edmond"))->then->succeedWith("yes the names are same")->elseWhenever(assertIts::equal($input_name, "charles")))->then->succeedWith("yes the names are charles")->otherwise()->succeedWith("Its some other name")->done()
     *
     * @return $this
     */
    public function done()
    {
        if(!$this->isCurrentDBAction) {return $this;
        }   

        $this->execOrNot = true;

        return $this;   
    }
}
