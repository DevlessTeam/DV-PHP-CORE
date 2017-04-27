<?php 

class Action {
	private $object;
	
	/**
	 * Primary constructor.
	 */
	public function __construct($json) {
		$this->object = is_object($json) ? $json : new stdClass;
	}
	
	/**
	 * Gets actionMeta.
	 */
	public function getActionMeta() {
		return @$this->object->ActionMeta;
	}
	
	/**
	 * Gets actionTypeId.
	 */
	public function getActionTypeId() {
		return @$this->object->ActionTypeId;
	}
	
	/**
	 * Gets campaignId.
	 */
	public function getCampaignId() {
		return @$this->object->CampaignId;
	}
	
	/**
	 * Gets id.
	 */
	public function getId() {
		return @$this->object->Id;
	}
	
	/**
	 * Gets isActive.
	 */
	public function isActive() {
		return @$this->object->IsActive;
	}
}
