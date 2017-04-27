<?php 

class AccountProfile {
	
	/**
	 * Data fields.
	 */
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
	 * Gets accountManager.
	 */
	public function getAccountManager() {
		return @$this->object->AccountManager;
	}
	
	/**
	 * Gets accountNumber.
	 */
	public function getAccountNumber() {
		return @$this->object->AccountNumber;
	}
	
	/**
	 * Gets accountStatus.
	 */
	public function getAccountStatus() {
		return @$this->object->AccountStatus;
	}
	
	/**
	 * Gets balance.
	 */
	public function getBalance() {
		return @$this->object->Balance;
	}
	
	/**
	 * Gets company.
	 */
	public function getCompany() {
		return @$this->object->Company;
	}
	
	/**
	 * Gets credit.
	 */
	public function getCredit() {
		return @$this->object->Credit;
	}
	
	/**
	 * Gets emailAddress.
	 */
	public function getEmailAddress() {
		return @$this->object->EmailAddress;
	}
	
	/**
	 * Gets lastAccessed.
	 */
	public function getLastAccessed() {
		return @$this->object->LastAccessed;
	}
	
	/**
	 * Gets mobileNumber.
	 */
	public function getMobileNumber() {
		return @$this->object->MobileNumber;
	}
	
	/**
	 * Gets numberOfServices.
	 */
	public function getNumberOfServices() {
		return @$this->object->NumberOfServices;
	}
	
	/**
	 * Gets primaryContact.
	 */
	public function getPrimaryContact() {
		return @$this->object->PrimaryContact;
	}
	
	/**
	 * Gets unpostedBalance.
	 */
	public function getUnpostedBalance() {
		return @$this->object->UnpostedBalance;
	}
}