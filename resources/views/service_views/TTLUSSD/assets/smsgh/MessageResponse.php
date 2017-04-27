<?php 

class MessageResponse {
	private $object;
	
	/**
	 * Primary constructor.
	 */
	public function __construct($json) {
		$this->object = is_object($json) ? $json : new stdClass;
	}
	
	/**
	 * Gets clientReference.
	 */
	public function getClientReference() {
		return @$this->object->ClientReference;
	}
	
	/**
	 * Gets detail.
	 */
	public function getDetail() {
		return @$this->object->Detail;
	}
	
	/**
	 * Gets messageId.
	 */
	public function getMessageId() {
		return @$this->object->MessageId;
	}
	
	/**
	 * Gets networkId.
	 */
	public function getNetworkId() {
		return @$this->object->NetworkId;
	}
	
	/**
	 * Gets rate.
	 */
	public function getRate() {
		return @$this->object->Rate;
	}
	
	/**
	 * Gets status.
	 */
	public function getStatus() {
		return @$this->object->Status;
	}
}
