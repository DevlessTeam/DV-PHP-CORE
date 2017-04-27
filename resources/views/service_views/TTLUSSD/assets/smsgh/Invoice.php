<?php 

class Invoice {
	private $object;
	
	/**
	 * Primary constructor.
	 */
	public function __construct($json) {
		$this->object = is_object($json) ? $json : new stdClass;
	}
	
	/**
	 * Gets amount.
	 */
	public function getAmount() {
		return @$this->object->Amount;
	}
	
	/**
	 * Gets created.
	 */
	public function getCreated() {
		return @$this->object->Created;
	}
	
	/**
	 * Gets description.
	 */
	public function getDescription() {
		return @$this->object->Description;
	}
	
	/**
	 * Gets dueDate.
	 */
	public function getDueDate() {
		return @$this->object->DueDate;
	}
	
	/**
	 * Gets ending.
	 */
	public function getEnding() {
		return @$this->object->Ending;
	}
	
	/**
	 * Gets id.
	 */
	public function getId() {
		return @$this->object->Id;
	}
	
	/**
	 * Gets isPaid.
	 */
	public function isPaid() {
		return @$this->object->IsPaid;
	}
	
	/**
	 * Gets Type.
	 */
	public function getType() {
		return @$this->object->Type;
	}
}
