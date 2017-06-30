<?php

namespace Devless\RulesEngine;

trait tableActions
{
    /**
     * Checks if a table is being queried for data then the code attached to it will run. This will run twice before and after the data is being queried.
     *
     * @return $this
     */
    public function onQuery()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->actionType == 'GET');

        return $this;
    }
    /**
     * Checks if data is being updated on a table then the code attached to it will run.This will run twice before and after the data is being updated.
     *
     * @return $this
     */
    public function onUpdate()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->actionType == 'PATCH');

        return $this;
    }
    /**
     * Checks if data is being added to table then the code attached to it will run. This will run twice before and after the data is being added
     *
     * @return $this
     */
    public function onCreate()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->actionType == 'POST');

        return $this;
    }
    /**
     * Check if data is being deleted from a table. This will run code attached to it before and after its being run 
     *
     * @return $this
     */
    public function onDelete()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->actionType == 'DELETE');

        return $this;
    }
    /**
     * Checks if a table is about to be queried for data .The code attached to it will run before the data is queried
     *
     * @return $this
     */
    public function beforeQuerying()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'before' && $this->actionType == 'GET');

        return $this;
    }
    /**
     * Checks if  data is about to be added to a table and runs the code attached to it before this happens
     *
     * @return $this
     */
    public function beforeCreating()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'before' && $this->actionType == 'POST');

        return $this;
    }
    /**
     * Checks if table data is about to be updated, and will run the code attached to it before the action is performed
     *
     * @return $this
     */
    public function beforeUpdating()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'before' && $this->actionType == 'PATCH');

        return $this;
    }
    /**
     * Check if data is about to be deleted from table. Then run the code attached to the action before its being performed. This allows you to perform actions such as `afterQuering()->mutateResponseMessage("the response message has been altered")
     *
     * @return $this
     */
    public function beforeDeleting()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'before' && $this->actionType == 'DELETE');

        return $this;
    }

     /**
     * Runs code attached to this method just before the response is being returned to the client. This allows you to perform actions such as `afterQuering()->whenever($rules->status_code == 625)->mutateResponseMessage("the response message has been altered")
     *
     * @return $this
     */
    public function afterQuerying()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'after' && $this->actionType == 'GET');

        return $this;
        
    }
    /**
     * Runs code attached to this method after the data has been added to the DB. This allows you to perform actions such as `afterCreating()->whenever($rules->status_code == 625)->mutateResponseMessage("the response message has been altered") 
     *
     * @return $this
     */
    public function afterCreating()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'after' && $this->actionType == 'POST');

        return $this;
    }
    /**
     * Run code attached to this method after the data has been updated .  This allows you to perform actions such as `afterUpdating()->whenever($rules->status_code == 619)->mutateResponseMessage("the response message has been altered")
     *
     * @return $this
     */
    public function afterUpdating()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'after' && $this->actionType == 'PATCH');

        return $this;
    }
    /**
     * Runs code attached to the `afterDeleting()` method. This allows you to perform actions such as `afterDeleting()->whenever($rules->status_code == 636)->mutateResponseMessage("the response message has been altered")
     *
     * @return $this
     */
    public function afterDeleting()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->request_phase == 'after' && $this->actionType == 'DELETE');

        return $this;
    }

    /**
     * Code attached to the `onAnyRequest()` will run regardless of whether its a query, create delete or update action
     *
     * @return $this
     */
    public function onAnyRequest()
    {
        $this->execOrNot = $this->isCurrentDBAction = true;
        return $this;
    }

}
