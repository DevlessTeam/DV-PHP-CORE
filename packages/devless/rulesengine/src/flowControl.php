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
        if (!$this->execOrNot) {
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
        $this->execOrNot = $this->assertion['otherwise'] =
            (!$this->assertion['whenever']) ?: false;

        if (!$this->execOrNot) {
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
        $this->execOrNot = $this->assertion['otherwise'] =
            (!$this->assertion['elseWhenever'] && !$this->assertion['whenever']) ?: false;

        if (!$this->execOrNot) {
            return $this;
        }
        $this->called['otherwise'] = true;
        $this->execOrNot = $this->assertion['otherwise'];

        return $this;
    }
}
