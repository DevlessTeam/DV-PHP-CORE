<?php

class NumberPlan {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json) {
        if ($json === null)
            $this->object = new stdClass;
        else if (is_object($json)) {
            $this->object = $json;

            $arr = array();
            if (isset($json->MoKeyWords))
                foreach ($json->MoKeyWords as $o)
                    $arr[] = new MoKeyWord($o);
            $this->object->MoKeyWords = $arr;

            $arr = array();
            if (isset($json->NumberPlanItems))
                foreach ($json->NumberPlanItems as $o)
                    $arr[] = new NumberPlanItem($o);
            $this->object->NumberPlanItems = $arr;

            if (isset($json->ServiceType))
                $this->object->ServiceType = new ServiceType($json->ServiceType);
        } else
            throw new Exception('Bad parameter');
    }

    /**
     * Gets accountId.
     */
    public function getAccountId() {
        return @$this->object->AccountId;
    }

    /**
     * Gets dateActivated.
     */
    public function getDateActivated() {
        return @$this->object->DateActivated;
    }

    /**
     * Gets dateCreated.
     */
    public function getDateCreated() {
        return @$this->object->DateCreated;
    }

    /**
     * Gets dateDeactivated.
     */
    public function getDateDeactivated() {
        return @$this->object->DateDeactivated;
    }

    /**
     * Gets dateExpiring.
     */
    public function getDateExpiring() {
        return @$this->object->DateExpiring;
    }

    /**
     * Gets description.
     */
    public function getDescription() {
        return @$this->object->Description;
    }

    /**
     * Gets id.
     */
    public function getId() {
        return @$this->object->Id;
    }

    /**
     * Gets initialCost.
     */
    public function getInitialCost() {
        return @$this->object->InitialCost;
    }

    /**
     * Gets isActive.
     */
    public function isActive() {
        return @$this->object->IsActive;
    }

    /**
     * Gets isPremium.
     */
    public function isPremium() {
        return @$this->object->IsPremium;
    }

    /**
     * Gets maxAllowedKeywords.
     */
    public function getMaxAllowedKeywords() {
        return @$this->object->MaxAllowedKeywords;
    }

    /**
     * Gets moKeyWords.
     */
    public function getMoKeyWords() {
        return @$this->object->MoKeyWords;
    }

    /**
     * Gets notes.
     */
    public function getNotes() {
        return @$this->object->Notes;
    }

    /**
     * Gets numberPlanItems.
     */
    public function getNumberPlanItems() {
        return @$this->object->NumberPlanItems;
    }

    /**
     * Gets periodicCostBasis.
     */
    public function getPeriodicCostBasis() {
        return @$this->object->PeriodicCostBasis;
    }

    /**
     * Gets serviceType.
     */
    public function getServiceType() {
        return @$this->object->ServiceType;
    }

}
