<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContactApi
 *
 * @author smsgh
 */
class ContactApi extends AbstractApi {

    public function __construct($apiHost, $enableConsoleLog = TRUE) {
        parent::__construct($apiHost, $enableConsoleLog);
    }

    /**
     * Gets a contact details
     * @param integer $contactId
     * @return Contact|HttpResponse|null
     * @throws ErrorException
     */
    public function getContact($contactId) {
        $resource = "/contacts/";
        if (is_null($contactId)) {
            throw new ErrorException("Parameter 'contactId' cannot be null");
        } elseif (!is_int($contactId)) {
            throw new ErrorException("Parameter 'contactId' must be an integer");
        }
        try {
            $resource .= $contactId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new Contact($json);
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Gets contacts list. The list can be paginated using the paginations arguments
     * $page and $pageSize. Also One can get the list of contacts based upon a particular and a particular
     * keyword.
     * @param integer $page The page number to display. The default is -1.
     * @param integer $pageSize he maximum number of items to be contained in a single page.
     * @param integer $groupId The ID of the group you want to view its contacts. 
     *          If this parameter is not supplied, the API will return contacts in any group.
     * @param string $search Query filter to narrow results of the request. 
     *          For example, a value of "Lu" will return contacts whose names begin with "Lu" such as Luiz, Lumba, etc.
     * @return ApiList|HttpResponse|null
     * @throws ErrorException
     */
    public function getContacts($page = -1, $pageSize = -1, $groupId = -1, $search = null) {
        $resource = "/contacts/";
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            if (!is_int($groupId)) {
                throw new ErrorException("Parameter 'groupId' must be an integer");
            }

            if (is_int($groupId) && $groupId > 0) {
                $params["GroupId"] = $groupId;
            }

            if ($search !== null && !is_string($search)) {
                throw new ErrorException("Parameter 'search' must be a string");
            }

            if ($search !== null) {
                $params["Search"] = $search;
            }
            $response = $this->httpClient->get($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new ApiList($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Creates a new Contact
     * @param mixed $contact The contact data. It can be an associative array or an instance of Contact
     * @return Contact|HttpResponse|null
     * @throws ErrorException
     */
    public function addContact($contact) {
        $resource = "/contacts/";
        if (is_null($contact)) {
            throw new ErrorException("Parameter 'contact' cannot be null");
        }

        if (!($contact instanceof Contact) || !is_array($contact)) {
            throw new ErrorException("Parameter 'contact' must be of type Contact");
        }
        try {
            $params = array();
            $params = is_array($contact) ? $contact : json_decode(JsonHelper::toJson($contact), true);
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Contact($json);
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
     * Updates a Contact
     * @param integer $contactId
     * @param mixed $data
     * @return boolean|HttpResponse
     * @throws ErrorException
     */
    public function updateContact($contactId, $data) {
        $resource = "/contacts/";
        if (is_null($contactId)) {
            throw new ErrorException("Parameter 'contactId' cannot be null");
        } elseif (!is_int($contactId)) {
            throw new ErrorException("Parameter 'contactId' must be an integer");
        }

        if (is_null($data)) {
            throw new ErrorException("Parameter 'data' cannot be null");
        } elseif ($data instanceof Contact || !is_array($data)) {
            throw new ErrorException("Parameter 'data' must be either an array or of type Contact");
        }

        try {
            $resource .= $contactId;
            $params = array();
            $params = is_array($data) ? $data : json_decode(JsonHelper::toJson($data), true);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    return TRUE;
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }

        return FALSE;
    }

    /**
     * 
     * @param integer $groupId
     * @param mixed $data
     * @return boolean|HttpResponse
     * @throws ErrorException
     */
    public function updateContactGroup($groupId, $data) {
        $resource = "/contacts/groups/";
        if (is_null($groupId)) {
            throw new ErrorException("Parameter 'groupId' cannot be null");
        } elseif (!is_int($groupId)) {
            throw new ErrorException("Parameter 'groupId' must be an integer");
        }

        if (is_null($data)) {
            throw new ErrorException("Parameter 'data' cannot be null");
        } elseif (!($data instanceof ContactGroup) || !is_array($data) || !is_string($data)) {
            throw new ErrorException("Parameter 'data' must be either an array or of type ContactGroup");
        }

        try {
            $resource .= $groupId;
            $params = array();
            if (is_string($data)) {
                $params["Name"] = $data;
            } else
                $params = is_array($data) ? $data : json_decode(JsonHelper::toJson($data), true);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    return TRUE;
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }

        return FALSE;
    }

    /**
     * Deletes a given Contact
     * @param integer $contactId
     * @return HttpResponse|boolean
     * @throws ErrorException
     */
    public function deleteContact($contactId) {
        $resource = "/contacts/";
        if (is_null($contactId)) {
            throw new ErrorException("Parameter 'contactId' cannot be null");
        } elseif (!is_int($contactId)) {
            throw new ErrorException("Parameter 'contactId' must be an integer");
        }


        try {
            $resource .= $contactId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK)
                    return TRUE;
                else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
    }

    /**
     * Gets contact groups list. The list can be paginated using the paginations arguments
     * $page and $pageSize. 
     * @param integer $page The page number to display. The default is -1.
     * @param integer $pageSize he maximum number of items to be contained in a single page.
     * @return ApiList|HttpResponse|null
     */
    public function getContactGroups($page = -1, $pageSize = -1) {
        $resource = "/contacts/groups/";
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            $response = $this->httpClient->get($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new ApiList($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Retrieves the details of a specific group based upon its ID.
     * @param integer $groupId
     * @return HttpResponse|null|ContactGroup
     * @throws ErrorException
     */
    public function getContactGroup($groupId) {
        $resource = "/contacts/groups/";
        if (is_null($groupId)) {
            throw new ErrorException("Parameter 'groupId' cannot be null");
        } elseif (!is_int($groupId)) {
            throw new ErrorException("Parameter 'groupId' must be an integer");
        }
        try {
            $resource .= $groupId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new ContactGroup($json);
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Deletes a contact group based upon its ID.
     * @param integer $groupId
     * @return HttpResponse|boolean
     * @throws ErrorException
     */
    public function deleteContactGroup($groupId) {
        $resource = "/contacts/groups/";
        if (is_null($groupId)) {
            throw new ErrorException("Parameter 'groupId' cannot be null");
        } elseif (!is_int($groupId)) {
            throw new ErrorException("Parameter 'groupId' must be an integer");
        }

        try {
            $resource .= $groupId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK)
                    return TRUE;
                else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
    }

    /**
     * Creates a new Contact Group
     * @param mixed $group The contact group data. It can be an associative array or an instance of ContactGroup
     * @return HttpResponse|null|ContactGroup
     * @throws ErrorException
     */
    public function addContactGroup($group) {
        $resource = "/contacts/groups/";
        if (is_null($group)) {
            throw new ErrorException("Parameter 'group' cannot be null");
        }

        if (!is_string($group) || !($group instanceof ContactGroup) || !is_array($group)) {
            throw new ErrorException("Parameter 'group' must be of type ContactGroup or an array or a string");
        }
        try {
            $params = array();
            if (is_string($group)) {
                $params["Name"] = $group;
            } else {
                $params = is_array($group) ? $group : json_decode(JsonHelper::toJson($group), true);
            }
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new ContactGroup($json);
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

}
