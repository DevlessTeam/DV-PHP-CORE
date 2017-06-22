<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;


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

        (!strlen($this->EVENT['user_id']) && !Helper::is_admin_login())? Helper::interrupt(628) : '';

        return $this;
    }

    /**
     * Lock down table access.
     *
     * @return instance
     */
    public function denieExternalAccess()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 0;
        Helper::interrupt(627);
        return $this;
    }

    /**
     * Open Up table access to everyone.
     *
     * @return instance
     */
    public function allowEnternalAccess()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 1;

        return $this;
    }

    public function grantOnlyAdminAccess()
    {
        if (!$this->execOrNot) {
            return $this;
        }

         (!\DB::table('users')->where('id', $this->EVENT['user_id'])->where('role',1)->first() && !Helper::is_admin_login() )?Helper::interrupt(628) : '';

        return $this;

    }
}
