<?php 

class Service {
	private $object;
	
	/**
	 * Primary constructor.
	 */
	public function __construct($json) {
		$this->object = is_object($json) ? $json : new stdClass;
	}
	
	/**
	 * Gets accountId.
	 */
	public function getAccountId() {
		return @$this->object->AccountId;
	}
	
	/**
	 * Gets billDate.
	 */
	public function getBillDate() {
		return @$this->object->BillDate;
	}
	
	/**
	 * Gets billingCycleId.
	 */
	public function getBillingCycleId() {
		return @$this->object->BillingCycleId;
	}
	
	/**
	 * Gets dateCreated.
	 */
	public function getDateCreated() {
		return @$this->object->DateCreated;
	}
	
	/**
	 * Gets description.
	 */
	public function getDescription() {
		return @$this->object->Description;
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
	 * Gets rate.
	 */
	public function getRate() {
		return @$this->object->Rate;
	}
	
	/**
	 * Gets serviceId.
	 */
	public function getServiceId() {
		return @$this->object->ServiceId;
	}
	
	/**
	 * Gets serviceStatusTypeId.
	 */
	public function getServiceStatusTypeId() {
		return @$this->object->ServiceStatusTypeId;
	}
	
	/**
	 * Gets serviceTypeId.
	 */
	public function getServiceTypeId() {
		return @$this->object->ServiceTypeId;
	}
}
