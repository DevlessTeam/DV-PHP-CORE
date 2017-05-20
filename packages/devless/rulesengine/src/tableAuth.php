<?php

namespace Devless\RulesEngine;

trait tableAuth
{
    /**
     * Convert table access right to authenticated.
     *
     * @return instance
     */
    public function authenticateUser()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 2;

        return $this;
    }

    /**
     * Lock down table access.
     *
     * @return instance
     */
    public function lockDownTable()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 0;

        return $this;
    }

    /**
     * Open Up table access to everyone.
     *
     * @return instance
     */
    public function makeTablePublic()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 1;

        return $this;
    }
}
