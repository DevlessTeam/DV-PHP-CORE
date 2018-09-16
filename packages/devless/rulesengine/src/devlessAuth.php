<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;

trait devlessAuth
{

    public function beforeSigningIn()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'before'
            && $this->getAuthAction() == 'login');
        return $this;
    }

    public function beforeSigningUp()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'before'
            && $this->getAuthAction() == 'signUp');

        return $this;
    }

    public function beforeQueryingProfile()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'before'
            && $this->getAuthAction() == 'getProfile');

        return $this;
    }

    public function beforeUpdatingProfile()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'before'
            && $this->getAuthAction() == 'updateProfile');
        return $this;
    }

    public function onSigningIn()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->getAuthAction() == 'login');
        return $this;
    }

    public function onSigningUp()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->getAuthAction() == 'signUp');
        return $this;
    }

    public function onQueringProfile()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->getAuthAction() == 'getProfile');
        return $this;
    }

    public function onProfileUpdate()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->getAuthAction() == 'updateProfile');
        return $this;
    }

    public function afterSigningIn()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'after'
            && $this->getAuthAction() == 'login');
        return $this;
    }

    public function afterSigningUp()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'after'
            && $this->getAuthAction() == 'signUp');
        return $this;
    }

    public function afterQueryingProfile()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'after'
            && $this->getAuthAction() == 'getProfile');
        return $this;
    }

    public function afterUpdatingProfile()
    {
        $this->execOrNot = $this->isCurrentDBAction = ($this->EVENT['request_phase'] == 'after'
            && $this->getAuthAction() == 'updateProfile');
        return $this;
    }

    private function getAuthAction()
    {
        if (isset(Helper::query_string()['action'])) {
            $action = Helper::query_string()['action'][0];
            return $action;
        } else {
            // $this->stopAndOutput(700, 'Hmm seems you are trying to use auth actions in a normal service.', []);
        }
    }
}
