<?php

namespace Devless\RulesEngine;

trait tableActions
{
    /**
     * Check if table is being queried.
     *
     * @return $this
     */
    public function onQuery()
    {
        $this->execOrNot = ($this->actionType == 'GET');

        return $this;
    }
    /**
     * Check if data is being updated on table.
     *
     * @return $this
     */
    public function onUpdate()
    {
        $this->execOrNot = ($this->actionType == 'PATCH');

        return $this;
    }
    /**
     * Check if data is being added to table.
     *
     * @return $this
     */
    public function onCreate()
    {
        $this->execOrNot = ($this->actionType == 'POST');

        return $this;
    }
    /**
     * Check if data is being deleted from table.
     *
     * @return $this
     */
    public function onDelete()
    {
        $this->execOrNot = ($this->actionType == 'DELETE');

        return $this;
    }
}
