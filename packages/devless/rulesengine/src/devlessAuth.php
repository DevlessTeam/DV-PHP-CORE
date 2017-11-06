<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;

trait devlessAuth 
{
	public function extendUsersTableWith($service_name, $table_name)
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;
	}

	public function beforeSigning() 
	{

		$this->execOrNot = 
			($this->request_phase_is('before') and $this->action_type_is('login') );
		return $this;	
	}

	public function beforeSigningUp() 
	{
		$this->execOrNot = 
			($this->request_phase_is('before') and $this->action_type_is('signUp') );
		return $this;	
	}

	public function beforeQueryingProfile() 
	{
		$this->execOrNot = 
			($this->request_phase_is('before') and $this->action_type_is('profile') );
		return $this;	
	}	

	public function beforeUpdatingProfile() 
	{
		$this->execOrNot = 
			($this->request_phase_is('before') and $this->action_type_is('updateProfile') );
		return $this;	
	}


	public function onSigning() 
	{
		$this->execOrNot = 
			($this->action_type_is('login') );
		return $this;	
	}

	public function onSigningUp() 
	{
		$this->execOrNot = 
			($this->action_type_is('signUp') );

		return $this;
	}

	public function onQueringProfile() 
	{
		$this->execOrNot = 
			($this->action_type_is('profile') );

		return $this;
	}

	public function onProfileUpdate() 
	{
		$this->execOrNot = 
			($this->action_type_is('updateProfile') );

		return $this;	
	}

	public function afterSigning() 
	{
		$this->execOrNot = 
			($this->request_phase_is('after') and $this->action_type_is('login') );

		return $this;	
	}

	public function afterSigningUp() 
	{
		$this->execOrNot = 
			($this->request_phase_is('after') and $this->action_type_is('signUp') );

		return $this;	
	}

	public function afterQueryingProfile() 
	{
		$this->execOrNot = 
			($this->request_phase_is('after') and $this->action_type_is('profile') );

		return $this;	
	}

	public function afterUpdatingProfile() 
	{
		$this->execOrNot = 
			($this->request_phase_is('after') and $this->action_type_is('updatProfile') );

		return $this;
	}
 
	private function action_type_is($type) 
	{
		if(!isset(Helper::query_string()['action']) && !isset(Helper::query_string()['action'][0]))return false;
		return (Helper::query_string()['action'][0] == $type);
	}

	private function request_phase_is($type) 
	{
		return ($this->EVENT['request_phase'] == $type);
	}
}

