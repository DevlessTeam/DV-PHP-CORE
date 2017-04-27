<?php

class Setting {

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
     * Gets countryCode.
     */
    public function getCountryCode() {
        return @$this->object->CountryCode;
    }

    /**
     * Gets deliveryReportNotificationUrl.
     */
    public function getDeliveryReportNotificationUrl() {
        return @$this->object->DeliveryReportNotificationUrl;
    }

    /**
     * Gets emailDailySummary.
     */
    public function getEmailDailySummary() {
        return @$this->object->EmailDailySummary;
    }

    /**
     * Gets emailInvoiceReminders.
     */
    public function getEmailInvoiceReminders() {
        return @$this->object->EmailInvoiceReminders;
    }

    /**
     * Gets emailMaintenance.
     */
    public function getEmailMaintenance() {
        return @$this->object->EmailMaintenance;
    }

    /**
     * Gets emailNewInvoice.
     */
    public function getEmailNewInvoice() {
        return @$this->object->EmailNewInvoice;
    }

    /**
     * Gets smsFortnightBalance.
     */
    public function getSmsFortnightBalance() {
        return @$this->object->SmsFortnightBalance;
    }

    /**
     * Gets smsLowBalanceNotification.
     */
    public function getSmsLowBalanceNotification() {
        return @$this->object->SmsLowBalanceNotification;
    }

    /**
     * Gets smsMaintenance.
     */
    public function getSmsMaintenance() {
        return @$this->object->SmsMaintenance;
    }

    /**
     * Gets smsPromotionalMessages.
     */
    public function getSmsPromotionalMessages() {
        return @$this->object->SmsPromotionalMessages;
    }

    /**
     * Gets smsTopUpNotification.
     */
    public function getSmsTopUpNotification() {
        return @$this->object->SmsTopUpNotification;
    }

    /**
     * Gets timeZone.
     */
    public function getTimeZone() {
        return @$this->object->TimeZone;
    }

    /**
     * Sets countryCode.
     */
    public function setCountryCode($value) {
        if (is_string($value)) {
            $this->object->CountryCode = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets deliveryReportNotificationUrl.
     */
    public function setDeliveryReportNotificationUrl($value) {
        if (is_string($value)) {
            $this->object->DeliveryReportNotificationUrl = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets emailDailySummary.
     */
    public function setEmailDailySummary($value) {
        if (is_bool($value)) {
            $this->object->EmailDailySummary = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets emailInvoiceReminders.
     */
    public function setEmailInvoiceReminders($value) {
        if (is_bool($value)) {
            $this->object->EmailInvoiceReminders = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets emailMaintenance.
     */
    public function setEmailMaintenance($value) {
        if (is_bool($value)) {
            $this->object->EmailMaintenance = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets emailNewInvoice.
     */
    public function setEmailNewInvoice($value) {
        if (is_bool($value)) {
            $this->object->EmailNewInvoice = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets smsFortnightBalance.
     */
    public function setSmsFortnightBalance($value) {
        if (is_bool($value)) {
            $this->object->SmsFortnightBalance = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets smsLowBalanceNotification.
     */
    public function setSmsLowBalanceNotification($value) {
        if (is_bool($value)) {
            $this->object->SmsLowBalanceNotification = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets smsMaintenance.
     */
    public function setSmsMaintenance($value) {
        if (is_bool($value)) {
            $this->object->SmsMaintenance = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets smsPromotionalMessages.
     */
    public function setSmsPromotionalMessages($value) {
        if (is_bool($value)) {
            $this->object->SmsPromotionalMessages = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets smsTopUpNotification.
     */
    public function setSmsTopUpNotification($value) {
        if (is_bool($value)) {
            $this->object->SmsTopUpNotification = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets timeZone.
     */
    public function setTimeZone($value) {
        if (is_string($value)) {
            $this->object->TimeZone = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

}
