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
        $this->isCurrentDBAction = ($this->actionType == 'GET');

        return $this;
    }
    /**
     * Check if data is being updated on table.
     *
     * @return $this
     */
    public function onUpdate()
    {
        $this->isCurrentDBAction = ($this->actionType == 'PATCH');

        return $this;
    }
    /**
     * Check if data is being added to table.
     *
     * @return $this
     */
    public function onCreate()
    {
        $this->isCurrentDBAction = ($this->actionType == 'POST');

        return $this;
    }
    /**
     * Check if data is being deleted from table.
     *
     * @return $this
     */
    public function onDelete()
    {
        $this->isCurrentDBAction = ($this->actionType == 'DELETE');

        return $this;
    }
}
