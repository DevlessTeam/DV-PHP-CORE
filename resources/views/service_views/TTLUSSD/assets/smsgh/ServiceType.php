<?php 

class ServiceType {
	private $object;
	
	/**
	 * Primary constructor.
	 */
	public function __construct($json) {
		$this->object = is_object($json) ? $json : new stdClass;
	}
	
	/**
	 * Gets descriptor.
	 */
	public function getDescriptor() {
		return @$this->object->Descriptor;
	}
	
	/**
	 * Gets isCreditBased.
	 */
	public function isCreditBased() {
		return @$this->object->IsCreditBased;
	}
	
	/**
	 * Gets isPrepaid.
	 */
	public function isPrepaid() {
		return @$this->object->IsPrepaid;
	}
	
	/**
	 * Gets name.
	 */
	public function getName() {
		return @$this->object->Name;
	}
	
	/**
	 * Gets rate.
	 */
	public function getRate() {
		return @$this->object->Rate;
	}
	
	/**
	 * Gets requiresActivation.
	 */
	public function getRequiresActivation() {
		return @$this->object->RequiresActivation;
	}
}
