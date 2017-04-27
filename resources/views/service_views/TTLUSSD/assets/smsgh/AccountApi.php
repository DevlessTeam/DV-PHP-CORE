<?php

/**
 * Description of AccountApi
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class AccountApi extends AbstractApi {

    public function __construct($apiHost, $enableConsoleLog = TRUE) {
        parent::__construct($apiHost, $enableConsoleLog);
    }

    /**
     * Returns the Account Profile
     * @return AccountProfile|HttpResponse|null
     */
    public function getProfile() {
        $resource = "/account/profile/";
        try {
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new AccountProfile($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Returns the Primary Account Contact
     * @return AccountContact|HttpResponse|null
     */
    public function getPrimaryContact() {
        $resource = "/account/primary_contact/";
        try {
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new AccountContact($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Returns the Billing Account Contact
     * @return AccountContact|HttpResponse|null
     */
    public function getBillingContact() {
        $resource = "/account/billing_contact/";
        try {
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new AccountContact($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Returns the Technical Account Contact
     * @return AccountContact|HttpResponse|null
     */
    public function getTechnicalContact() {
        $resource = "/account/technical_contact/";
        try {
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new AccountContact($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Returns the Account Contacts as an array.
     * @return array. Account Contact array
     */
    public function getContacts() {
        $resource = "/account/contacts/";
        try {
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    $contacts = array();
                    if (isset($json) && is_array($json)) {
                        /* @var $contact type */
                        /* @var $json type */
                        foreach ($json as $contact) {
                            $contacts[] = new AccountContact($contact);
                        }
                        return $contacts;
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Update an account contact details. 
     * @param mixed $request The Account Contact object to update
     * @param integer $accountContactId Description
     * @return boolean|HttpResponse. True when successful, False when fails
     * @throws ErrorException
     */
    public function updateAccountContact($accountContactId, $request) {
        $resource = "/account/contacts/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!is_array($request)) {
            throw new ErrorException("Parameter 'request' must be an array");
        }

        if (is_null($accountContactId)) {
            throw new ErrorException("Parameter 'accountContactId' cannot be null");
        } elseif (!is_numeric($accountContactId)) {
            throw new ErrorException("Parameter 'accountContactId' must be an integer");
        }
        try {
            $resource .= $accountContactId;
            $response = $this->httpClient->put($resource, $request);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK)
                    return TRUE;
                else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return FALSE;
    }

    /**
     * Get the list of Account Services. The list can be filtered through the pagination
     * filters parameters. For example to get the whole list one just has to call the function 
     * without the parameters like the following:
     *          getServices()
     * @param integer $pageSize The Number of elements to be displayed on a page.
     * @param integer $pageIndex The current Page number to display.
     * @return ApiList|HttpResponse|null The list of services is returned or NULL.
     */
    public function getServices($pageSize = -1, $pageIndex = -1) {
        $resource = "/account/services/";
        $params = array();
        if (is_int($pageIndex) && $pageIndex > 0) {
            $params["Page"] = $pageIndex;
        }

        if (is_int($pageSize) && $pageSize > 0) {
            $params["PageSize"] = $pageSize;
        }

        try {
            $response = $this->httpClient->get($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new ApiList($json);
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Get the Account Settings or Preferences
     * @return Setting|HttpResponse|null Account Settings
     */
    public function getSettings() {
        $resource = "/account/settings/";
        try {
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Setting($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Update Account Settings or Preferences and returns the updated preference object.
     * In case of errors an error is triggered
     * @param mixed $request Preference Object to update
     * @return Setting|HttpResponse|null Updated preference
     * @throws ErrorException
     */
    public function updateAccountSettings($request) {
        $resource = "/account/settings/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'preference' cannot be null");
        } elseif (!is_array($request) || !($request instanceof Setting)) {
            throw new ErrorException("Parameter 'request' must be an array");
        }

        try {
            $params = array();
            $params = is_array($request) ? $request : json_decode(JsonHelper::toJson($request), true);
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Setting($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * 
     * @param double $longitude
     * @param double $latitude
     * @return mixed|HttpResponse|null
     * @throws ErrorException
     */
    public function getTopupLocations($longitude, $latitude) {
        $resource = "/topup/voucher/vendors/";
        if (!is_null($longitude) && is_double($longitude)) {
            throw new ErrorException("Parameter 'longitude' must be a double");
        }

        if (!is_null($latitude) && is_double($latitude)) {
            throw new ErrorException("Parameter 'latitude' must be a double");
        }
        try {
            $params = array();
            if (!is_null($longitude)) {
                $params["Longitude"] = $longitude;
            }
            if (!is_null($latitude)) {
                $params["Latitude"] = $latitude;
            }
            $response = $this->httpClient->get($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if ($json instanceof stdClass) {
                        $locations = array();
                        foreach ($json as $name => $value) {
                            if (strtolower($name) === "locations") {
                                foreach ($value as $o) {
                                    $locations[] = new TopupLocation($o);
                                }
                            }
                        }
                        return $locations;
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Get a voucher topup
     * @param string $voucherNumber
     * @return HttpResponse|Topup|null
     * @throws ErrorException
     */
    public function getVoucher($voucherNumber) {
        $resource = "/topup/voucher/";
        if (is_null($voucherNumber)) {
            throw new ErrorException("Parameter 'voucherNumber' cannot be null");
        } elseif (!is_string($voucherNumber)) {
            throw new ErrorException("Parameter 'voucherNumber' must be a string");
        }

        try {
            $resource .= $voucherNumber;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Topup($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

}
