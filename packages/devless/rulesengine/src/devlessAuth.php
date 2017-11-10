<?php

namespace Devless\RulesEngine;

trait devlessAuth 
{
	public function extendUsersTableWith()
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;
	}

	public function beforeSigning() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function beforeSigningUp() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function beforeQueryingProfile() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}	

	public function beforeUpdatingProfile() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}


	public function onSigning() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function onSigningUp() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function onQueringProfile() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function onProfileUpdate() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function afterSigning() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function afterSigningUp() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function afterQueryingProfile() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}

	public function afterUpdatingProfile() 
	{
		if (! $this->execOrNot) {
			return $this;
		}
		return $this;	
	}
}

