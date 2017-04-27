<?php 

class NumberPlanItem {
	private $object;
	
	/**
	 * Primary constructor.
	 */
	public function __construct($json) {
		$this->object = is_object($json) ? $json : new stdClass;
	}
	
	/**
	 * Gets id.
	 */
	public function getId() {
		return @$this->object->Id;
	}
	
	/**
	 * Gets network.
	 */
	public function getNetwork() {
		return @$this->object->Network;
	}
	
	/**
	 * Gets payout.
	 */
	public function getPayout() {
		return @$this->object->Payout;
	}
	
	/**
	 * Gets reversePayout.
	 */
	public function getReversePayout() {
		return @$this->object->ReversePayout;
	}
	
	/**
	 * Gets shortCode.
	 */
	public function getShortCode() {
		return @$this->object->ShortCode;
	}
}
