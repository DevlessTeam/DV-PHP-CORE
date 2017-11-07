<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;

trait tableAuth
{
    /**
     * Appending the authenticateUser method to any query action will force users to login to gain access to the system eg: ->beforeQuerying()->onTable('subscriptions')->autheticateUser()
     *
     * @return $this
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
     * In the event you do not want to grant external access to a table to any users you should use `denyExternalAccess` method . `->beforeQuerying()->onTable('subscriptions')->denyExternalAccess()` In this case no user will be able to access the `subscriptions` table
     *
     * @return $this
     */
    public function denyExternalAccess()
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
     * You may decide to set all your table access to either `authenticated` or `private` from within the `privacy tab` but then decide to make one or more tables public in this case use the `allowExternalAccess` method `->beforeQuerying()->onTable('news', 'tariffs')->allowExternalAccess()`
     *
     * @return $this
     */
    public function allowExternalAccess()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 1;

        return $this;
    }

    /**
     * You may decide to provide access of some tables to just the admin instead locking them up totally . To do this use the `grantOnlyAdminAccess` method.`->beforeQuerying()->onTable('news', 'tariffs')->grantOnlyAdminAccess()`
     * @return $this
     */
    public function grantOnlyAdminAccess()
    {
        if (!$this->execOrNot) {
            return $this;
        }

         (!\DB::table('users')->where('id', $this->EVENT['user_id'])->where('role', 1)->first() && !Helper::is_admin_login() )?Helper::interrupt(628) : '';

        return $this;
    }
}
