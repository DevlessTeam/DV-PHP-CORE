<?php

/**
 * 
 *
 * @author Arsene Tochemey GANDOTE
 */
class MessagingApi extends AbstractApi {

    public function __construct($apiHost, $enableConsoleLog = TRUE) {
        parent::__construct($apiHost, $enableConsoleLog);
    }

    /**
     * Returns the List of Sender IDs. It returns a paginated list of Sender IDs.
     * if no pagination option is provided it will return the whole list of Sender IDs.
     * @param integer $page The  page number
     * @param integer $pageSize The page size
     * @return ApiList|HttpResponse|null The list of Sender IDs
     */
    public function getSenderIds($page = -1, $pageSize = -1) {
        $resource = "/senders/";
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
     * Return the details of a Sender ID.
     * @param integer $senderId The Sender ID ID.
     * @return Sender|HttpResponse|null The given Sender ID or null
     * @throws ErrorException
     */
    public function getSenderId($senderId) {
        $resource = "/senders/";
        if (is_null($senderId)) {
            throw new ErrorException("Parameter 'senderId' cannot be null");
        } elseif (!is_int($senderId)) {
            throw new ErrorException("Parameter 'senderId' must be an integer");
        }

        try {
            if (is_int($senderId)) {
                $resource .= $senderId;
                $response = $this->httpClient->get($resource);
                if ($response instanceof HttpResponse) {
                    if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                        $json = JsonHelper::getJson($response->getBody());
                        if (isset($json))
                            return new Sender($json);
                    } else
                        return $response;
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Add a new Sender ID. It returns the created Sender ID. 
     * @param mixed $request Associative array containing the Sender Data to create 
     * or an instance of Sender(@see Sender)
     * @return Sender|HttpResponse|null
     * @throws ErrorException
     */
    public function addSenderId($request) {
        $resource = "/senders/";

        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!is_array($request) || !($request instanceof Sender)) {
            throw new ErrorException("Parameter 'request' must be an array or an instance of Sender");
        }
        try {
            $params = array();
            $params = is_array($request) ? $request : json_decode(JsonHelper::toJson($request), true);

            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new Sender($json);
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Add a new Message Template. It returns the created Message Template.
     * @param mixed $request associative array containing the message template to create.
     * @return MessageTemplate|HttpResponse|null 
     * @throws ErrorException
     */
    public function addMessageTemplate($request) {
        $resource = "/templates/";

        // Let us check whether the request is an array and not null
        if (!is_array($request) || !($request instanceof MessageTemplate)) {
            throw new ErrorException("Parameter 'request' must be an array or an instance of MessageTemplate");
        }

        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        }

        try {
            $params = array();
            $params = is_array($request) ? $request : json_decode(JsonHelper::toJson($request), true);
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new MessageTemplate($json);
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Get Message Templates. It returns a paginated list of message templates.
     * If no pagination option is provided it will return all the message templates.
     * @param integer $page The page index
     * @param integer $pageSize The page size.
     * @return ApiList|HttpResponse|null
     */
    public function getMessageTemplates($page = -1, $pageSize = -1) {
        $resource = "/templates/";
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
     * Gets the details of a given message template.
     * @param integer $templateId The message Template ID.
     * @return MessageTemplate|HttpResponse|null
     * @throws ErrorException
     */
    public function getMessageTemplate($templateId) {
        $resource = "/templates/";
        if (is_null($templateId)) {
            throw new ErrorException("Parameter 'templateId' cannot be null");
        } elseif (!is_int($templateId)) {
            throw new ErrorException("Parameter 'templateId' must be an integer");
        }

        try {
            $resource .= $templateId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new MessageTemplate($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Update the details of a given message template.
     * @param integer $messageTemplateId
     * @param array $request
     * @return MessageTemplate|HttpResponse|null
     * @throws ErrorException
     */
    public function updateMessageTemplate($messageTemplateId, $request) {
        $resource = "/templates/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } else if (!is_array($request)) {
            throw new ErrorException("Parameter 'request' must be an array");
        }

        if (is_null($messageTemplateId)) {
            throw new ErrorException("Parameter 'messageTemplateId' cannot be null");
        } elseif (!is_int($messageTemplateId)) {
            throw new ErrorException("Parameter 'messageTemplateId' must be an integer");
        }
        try {
            $resource .= $messageTemplateId;
            $response = $this->httpClient->put($resource, $request);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new MessageTemplate($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Update the details of a given sender ID.
     * @param integer $senderId
     * @param array $request
     * @return Sender|HttpResponse|null
     * @throws ErrorException
     */
    public function updateSenderId($senderId, $request) {
        $resource = "/senders/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'senderId' cannot be null");
        } elseif (!is_array($request)) {
            throw new ErrorException("Parameter 'request' must be an array");
        }

        if (is_null($senderId)) {
            throw new ErrorException("Parameter 'senderId' cannot be null");
        } elseif (!is_int($senderId)) {
            throw new ErrorException("Parameter 'senderId' must be an integer");
        }
        try {
            $resource .= $senderId;
            $response = $this->httpClient->put($resource, $request);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new Sender($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Delete a Sender ID.
     * @param integer $senderId
     * @return boolean|HttpResponse
     * @throws ErrorException
     */
    public function deleteSenderId($senderId) {
        $resource = "/senders/";
        if (is_null($senderId)) {
            throw new ErrorException("Parameter 'senderId' cannot be null");
        } elseif (!is_int($senderId)) {
            throw new ErrorException("Parameter 'senderId' must be an integer");
        }
        try {
            $resource .= $senderId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_NO_CONTENT) {
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
     * Delete a Message Template.
     * @param integer $templateId
     * @return boolean|HttpResponse
     * @throws ErrorException
     */
    public function deleteMessageTemplate($templateId) {
        $resource = "/templates/";
        if (is_null($templateId)) {
            throw new ErrorException("Parameter 'templateId' cannot be null");
        } elseif (!is_int($templateId)) {
            throw new ErrorException("Parameter 'templateId' must be an integer");
        }
        try {
            $resource .= $templateId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_NO_CONTENT) {
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
     * Get Number Plans. It returns a paginated list of number plans.
     * If no pagination options is set all number plans are returned.
     * You can also specify the type of number plan to get.
     * @param integer $page The page index
     * @param integer $pageSize The page size
     * @param integer $type The number plan type. Possible values are 0, 2 and 3.
     * @return ApiList|HttpResponse|null
     */
    public function getNumberPlans($page = -1, $pageSize = -1, $type = -1) {
        $resource = "/numberplans/";
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            if (is_int($type) && $type > 0) {
                $params["Type"] = $type;
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
     * Gets MoKeywords for a given number plan. It return a paginated list of MoKeywords when the pagination options are 
     * set. On the contrary it will return all the MoKeywords of that number plan.
     * @param integer $numberPlanId The Number Plan ID
     * @param int $page Page Index
     * @param int $pageSize Page Size
     * @return ApiList|HttpResponse|null
     * @throws ErrorException
     */
    public function getNumberPlanMoKeywords($numberPlanId, $page = -1, $pageSize = -1) {
        $resource = "/numberplans/";
        if (is_null($numberPlanId)) {
            throw new ErrorException("Parameter 'numberPlanId' cannot be null ");
        } elseif (!is_int($numberPlanId)) {
            throw new ErrorException("Parameter 'numberPlanId' must be an integer");
        }
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            $resource .= "$numberPlanId/keywords";
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
     * Gets MoKeywords for a given Campaing. It return a paginated list of MoKeywords when the pagination options are 
     * set. On the contrary it will return all the MoKeywords of that Campaing.
     * @param integer $campaignId The Campaign ID
     * @param int $page Page Index
     * @param int $pageSize Page Size
     * @return ApiList|HttpResponse|null
     * @throws ErrorException
     */
    public function getCampaignMoKeywords($campaignId, $page = -1, $pageSize = -1) {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            $resource .= "$campaignId/keywords";
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
     * Get Campaigns. It returns a paginated list of campaigns.
     * If no pagination options is set all campaigns are returned.
     * @param int $page Page Index
     * @param int $pageSize Page Size
     * @return ApiList|HttpResponse|null
     */
    public function getCampaigns($page = -1, $pageSize = -1) {
        $resource = "/campaigns/";
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
     * Return the details of a Campaign.
     * @param integer $campaignId The Campaign ID.
     * @return Campaign|HttpResponse|null The given Campaign or null
     * @throws ErrorException
     */
    public function getCampaign($campaignId) {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        try {
            $resource .= $campaignId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Return the details of a NumberPlan.
     * @param integer $numberPlanId The NumberPlan ID.
     * @return NumberPlan|HttpResponse|null The given Campaign or null
     * @throws ErrorException
     */
    public function getNumberPlan($numberPlanId) {
        $resource = "/numberplans/";
        if (is_null($numberPlanId)) {
            throw new ErrorException("Parameter 'numberPlanId' cannot be null");
        } elseif (!is_int($numberPlanId)) {
            throw new ErrorException("Parameter 'numberPlanId' must be an integer");
        }

        try {
            $resource .= $numberPlanId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new NumberPlan($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Return the details of a MoKeyword.
     * @param integer $keywordId The NumberPlan ID.
     * @return MoKeyword|HttpResponse|null The given Campaign or null
     * @throws ErrorException
     */
    public function getMoKeyword($keywordId) {
        $resource = "/keywords/";
        if (is_null($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' cannot be null");
        } elseif (!is_int($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' must be an integer");
        }

        try {
            $resource .= $keywordId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new MoKeyWord($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Creates a new campaign.
     * @param mixed $request
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function addCampaign($request) {
        $resource = "/campaigns/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!is_array($request) || !($request instanceof Campaign)) {
            throw new ErrorException("Parameter 'request' must be an array or an instance of Campaign");
        }

        try {
            $params = array();
            $params = is_array($request) ? $request : json_decode(JsonHelper::toJson($request), true);
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
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
     * Creates a new MoKeyword.
     * @param mixed $request It can be an associative array or an instance of the MoKeyWord(@see MoKeyWord).
     * @example :
     *          $request = array("NumberPlanId" => 1, "Keyword" => "word", "Alias5" => "" .....)
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function addMoKeyword($request) {
        $resource = "/keywords/";

        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!is_array($request) || !($request instanceof MoKeyWord)) {
            throw new ErrorException("Parameter 'request' must be an array or an instance of MoKeyWord");
        }

        try {
            $params = array();
            $params = is_array($request) ? $request : json_decode(JsonHelper::toJson($request), true);
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new MoKeyWord($json);
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
     * Update a given Campaign
     * @param integer $campaignId
     * @param array $request The Campaign data to updaye
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function updateCampaign($campaignId, $request) {
        $resource = "/campaigns/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!is_array($request)) {
            throw new ErrorException("Parameter 'request' must be an array");
        }

        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }
        try {
            $resource .= $campaignId;
            $response = $this->httpClient->put($resource, $request);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Update a given MoKeyword.
     * @param integer $keywordId
     * @param array $request The MoKeyword data to update
     * @return MoKeyWord|HttpResponse|null
     * @throws ErrorException
     */
    public function updateMoKeyword($keywordId, $request) {
        $resource = "/keywords/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!is_array($request)) {
            throw new ErrorException("Parameter 'request' must be an array");
        }

        if (is_null($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' cannot be null");
        } elseif (!is_int($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' must be an integer");
        }
        try {
            $resource .= $keywordId;
            $response = $this->httpClient->put($resource, $request);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new MoKeyWord($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Deletes a given Campaign
     * @param integer $campaignId The Campaign ID to delete
     * @return boolean|HttpResponse
     * @throws ErrorException
     */
    public function deleteCampaign($campaignId) {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }
        try {
            $resource .= $campaignId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_NO_CONTENT)
                    return TRUE;
                else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return FALSE;
    }

    /**
     * Get MoKeywords. It returns a paginated list of MoKeywords.
     * If no pagination options is set all MoKeywords are returned.
     * @param integer $page The Page index
     * @param integer $pageSize The Page size
     * @return ApiList|HttpResponse|null
     */
    public function getMoKeywords($page = -1, $pageSize = -1) {
        $resource = "/keywords/";
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
     * Deletes a given MoKeyword
     * @param integer $keywordId The MoKeyord ID to delete
     * @return boolean|HttpResponse
     * @throws ErrorException
     */
    public function deleteMoKeyword($keywordId) {
        $resource = "/keywords/";
        if (is_null($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' cannot be null.");
        } elseif (!is_int($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' must be an integer");
        }
        try {
            $resource .= $keywordId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_NO_CONTENT)
                    return TRUE;
                else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return FALSE;
    }

    /**
     * Add an MoKeyword to a given a Campaign
     * @param integer $campaignId
     * @param integer $keywordId
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function updateCampaignMoKeyword($campaignId, $keywordId) {
        $resource = "/campaigns/";

        if (is_null($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' cannot be null.");
        } elseif (!is_int($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' must be an integer");
        }

        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        try {
            $resource .= "$campaignId/keywords/$keywordId";
            $response = $this->httpClient->put($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Deletes a Campaign MoKeyword
     * @param integer $campaignId
     * @param integer $keywordId
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function deleteCampaignMoKeyword($campaignId, $keywordId) {
        $resource = "/campaigns/";

        if (is_null($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' cannot be null.");
        } elseif (!is_int($keywordId)) {
            throw new ErrorException("Parameter 'keywordId' must be an integer");
        }

        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        try {
            $resource .= "$campaignId/keywords/$keywordId";
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
                    }
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Gets Actions for a given Campaing. It return a paginated list of Actions when the pagination options are 
     * set. On the contrary it will return all the Actions of that Campaing.
     * @param integer $campaignId The Campaign ID
     * @param int $page Page Index
     * @param int $pageSize Page Size
     * @return ApiList|HttpResponse|null
     * @throws ErrorException
     */
    public function getCampaignActions($campaignId, $page = -1, $pageSize = -1) {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }
        try {
            $params = array();
            if (is_int($page) && $page > 0) {
                $params["Page"] = $page;
            }

            if (is_int($pageSize) && $pageSize > 0) {
                $params["PageSize"] = $pageSize;
            }

            $resource .= "$campaignId/actions";
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
     * Adds a default reply text action to campaign.
     * @param integer $campaignId
     * @param string $message
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function setCampaignDefaultReplyTextAction($campaignId, $message) {
        $resource = "/campaigns/";

        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        if (is_null($message)) {
            throw new ErrorException("Parameter 'message' cannot be null ");
        } elseif (!is_string($message)) {
            throw new ErrorException("Parameter 'message' must be a string");
        }

        try {
            $params = array();
            $params["message"] = $message;

            $resource .= "/$campaignId/actions/default_reply";
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
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
     * Adds a dynamic URL action, with send response, to campaign.
     * @param integer $campaignId
     * @param string $url the dynamic URL to add.
     * @param string $sendResponse a Yes or No value.
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function setCampaignDynamicUrlAction($campaignId, $url, $sendResponse = 'No') {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        if (is_null($url)) {
            throw new ErrorException("Paramter 'url' cannot be null");
        } elseif (!is_string($url)) {
            throw new ErrorException("Parameter 'url' must be a string");
        } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new ErrorException("Parameter 'email' must be a valid url");
        }

        if (is_null($sendResponse)) {
            throw new ErrorException("Parameter 'sendResponse' cannot be null.");
        } elseif (!is_string($sendResponse)) {
            throw new ErrorException("Parameter 'sendResponse' must be a string");
        }
        try {
            $params = array();
            $params["url"] = $url;
            $params["send_response"] = $sendResponse;

            $resource .= "/$campaignId/actions/dynamic_url";
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
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
     * Adds an email address action to campaign.
     * @param integer $campaignId
     * @param string $email
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function setCampaignEmailAddressAction($campaignId, $email) {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        if (is_null($email)) {
            throw new ErrorException("Parameter 'email' cannot be null");
        } elseif (!is_string($email)) {
            throw new ErrorException("Parameter 'email' must be a string");
        } elseif (!CommonUtil::is_email($email, true)) {
            throw new ErrorException("Parameter 'email' must be a valid email address");
        }

        try {
            $params = array();
            $params["address"] = $email;

            $resource .= "/$campaignId/actions/email";
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
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
     * Adds a forward-to-mobile action to campaign.
     * @param integer $campaignId
     * @param string $number
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function setCampaignForwardToMobileAction($campaignId, $number) {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        if (is_null($number)) {
            throw new ErrorException("Parameter 'number' cannot be null");
        } elseif (!is_numeric($number)) {
            throw new ErrorException("Parameter 'number' must be a number");
        }

        try {
            $params = array();
            $params["number"] = $number;

            $resource .= "/$campaignId/actions/phone";
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
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
     * Adds a forward-to-SMPP action to campaign
     * @param integer $campaignId
     * @param string $apiId
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function setCampaignForwardToSmppAction($campaignId, $apiId) {
        $resource = "/campaigns/";
        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }

        if (is_null($apiId)) {
            throw new ErrorException("Parameter 'apiId' cannot be null");
        } elseif (!is_string($apiId)) {
            throw new ErrorException("Parameter 'apiId' must be a string");
        }
        try {
            $params = array();
            $params["api_id"] = $apiId;

            $resource .= "/$campaignId/actions/smpp";
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
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
     * Deletes a Campaign Action
     * @param integer $campaignId
     * @param integer $actionId
     * @return Campaign|HttpResponse|null
     * @throws ErrorException
     */
    public function deleteCampaignAction($campaignId, $actionId) {
        $resource = "/campaigns/";

        if (is_null($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' cannot be null");
        } elseif (!is_int($campaignId)) {
            throw new ErrorException("Parameter 'campaignId' must be an integer");
        }
        if (is_null($actionId)) {
            throw new ErrorException("Parameter 'actionId' cannot be null");
        } elseif (!is_int($actionId)) {
            throw new ErrorException("Parameter 'actionId' must be an integer");
        }

        try {
            $resource .= "/$campaignId/actions/$actionId";
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Campaign($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Sends a message.
     * @param mixed $message
     * @return MessageResponse|HttpResponse|null
     * @throws ErrorException
     */
    public function sendMessage($message) {
        $resource = "/messages/";

        if (is_null($message)) {
            throw new ErrorException("Parameter 'message' cannot be null");
        } elseif (!($message instanceof Message) && !is_array($message)) {
            throw new ErrorException("Parameter 'message' must be an instance of Message or an array");
        }

        try {
            $params = array();
            $params = is_array($message) ? $message : json_decode(JsonHelper::toJson($message), true);
            $params["Direction"] = MessageDirection::OUT;

            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new MessageResponse($json);
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
     * Sends a message
     * @param string $from
     * @param string $to
     * @param string $content
     * @param boolean $registeredDelivery
     * @param string $billingInfo 
     * @return MessageResponse|HttpResponse|null
     * @throws ErrorException
     */
    public function sendQuickMessage($from, $to, $content, $registeredDelivery = true, $billingInfo = null) {
        $resource = "/messages/";
        if (is_null($from)) {
            throw new ErrorException("Parameter 'from' cannot be null");
        } elseif (!is_string($from)) {
            throw new ErrorException("Parameter 'from' must be a string");
        }
        if (is_null($to)) {
            throw new ErrorException("Parameter 'to' cannot be null");
        } elseif (!is_string($to) || !is_numeric($to)) {
            throw new ErrorException("Parameter 'to' must be a string or a numeric string");
        }

        if (is_null($content)) {
            throw new ErrorException("Parameter 'content' cannot be null");
        } elseif (!is_string($content)) {
            throw new ErrorException("Parameter 'content' must be a string");
        }

        try {
            $params = array();
            $params["Direction"] = MessageDirection::OUT;
            $params["From"] = $from;
            $params["To"] = $to;
            $params["RegisteredDelivery"] = $registeredDelivery ? "true" : "false";
            $params["BillingInfo"] = is_null($billingInfo) ? "" : $billingInfo;
            $params["Content"] = $content;
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                    $json = JsonHelper::getJson($response->getBody());

                    if (isset($json)) {
                        return new MessageResponse($json);
                    }
                } else {
                    return $response;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
    }

    /**
     * Reschedule a Message
     * @param UUID $messageId The message ID it has to be a valid UUID
     * @param mixed $time The time it has to be an integer UNIX timestamp or a string time in this format (YYYY-MM-DD HH:MM:SS)
     * @return HttpResponse|MessageResponse|null
     * @throws ErrorException
     */
    public function scheduleMessage($messageId, $time) {
        $resource = "/messages/";

        if (is_null($messageId)) {
            throw new ErrorException("Parameter 'messageId' cannot be null");
        } elseif (!CommonUtil::is_uuid($messageId)) {
            throw new ErrorException("Parameter 'campaignId' must be a valid UUID");
        }
        if (is_null($time)) {
            throw new ErrorException("Parameter 'time' cannot be null");
        } elseif (!is_int($time) || !is_string($var)) {
            throw new ErrorException("Parameter 'time' must be an integer Unix timestamp or a string time in this format (YYYY-MM-DD HH:MM:SS)");
        }

        if (is_string($time)) {
            if (!CommonUtil::is_datetime($time)) {
                throw new ErrorException("Parameter 'time' must be a string time in this format (YYYY-MM-DD HH:MM:SS)");
            }
        } else if (is_int($time) && $time < 0) {
            throw new ErrorException("Parameter 'time' must be positive integer");
        }

        try {
            $resource .= $messageId;
            $params = array();
            $params["Time"] = is_string($time) ? $time : gmdate('Y-m-d H:i:s', $time);
            $response = $this->httpClient->put($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());

                    if (isset($json)) {
                        return new MessageResponse($json);
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
     * Cancels or Deletes a message
     * @param UUID $messageId
     * @return HttpResponse|MessageResponse|null
     * @throws ErrorException
     */
    public function deleteMessage($messageId) {
        $resource = "/messages/";

        if (is_null($messageId)) {
            throw new ErrorException("Parameter 'messageId' cannot be null");
        } elseif (!CommonUtil::is_uuid($messageId)) {
            throw new ErrorException("Parameter 'campaignId' must be a valid UUID");
        }

        try {
            $resource .= $messageId;
            $response = $this->httpClient->delete($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());

                    if (isset($json)) {
                        return new MessageResponse($json);
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
     * Gets a Message
     * @param UUID $messageId
     * @return Message|HttpResponse|null
     * @throws ErrorException
     */
    public function getMessage($messageId) {
        $resource = "/messages/";

        if (is_null($messageId)) {
            throw new ErrorException("Parameter 'messageId' cannot be null");
        } elseif (!CommonUtil::is_uuid($messageId)) {
            throw new ErrorException("Parameter 'messageId' must be a valid UUID");
        }

        try {
            $resource .= $messageId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new Message($json);
                } else
                    return $response;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return null;
    }

    /**
     * Gets Message based upon some filters
     * @param mixed $start The date to start querying from.
     * @param mixed $end The last possible time in the query.
     * @param integer $index The number of results to skip from the result set. The default is 0.
     * @param integer $limit The maximum number of results to return. This has a hard limit of 1000 messages.
     * @param boolean $pending A true or false value used to indicate if only scheduled messages should be returned in the result set. By default only sent message are returned
     * @param string $direction Used to filter the result by the direction of the message. Possible values are "in" (to return only inbound messages) and "out" (to return only outbound messages).
     * @return ApiList|HttpResponse|null
     * @throws ErrorException
     */
    public function getMessages($start = null, $end = null, $index = null, $limit = null, $pending = null, $direction = null) {
        $resource = "/messages/";
        if (!is_null($start)) {
            if (!is_int($start) || !is_string($start)) {
                throw new ErrorException("Parameter 'start' must be an integer Unix timestamp or a string time in this format (YYYY-MM-DD HH:MM:SS)");
            }

            if (is_string($start)) {
                if (!CommonUtil::is_datetime($time)) {
                    throw new ErrorException("Parameter 'start' must be a string time in this format (YYYY-MM-DD HH:MM:SS)");
                }
            }

            if (is_int($start) && $start < 0) {
                throw new ErrorException("Parameter 'start' must be a positive integer");
            }
        }
        if (!is_null($end)) {
            if (!is_int($end) || !is_string($end)) {
                throw new ErrorException("Parameter 'end' must be an integer Unix timestamp or a string time in this format (YYYY-MM-DD HH:MM:SS)");
            }

            if (is_string($end)) {
                if (!CommonUtil::is_datetime($time)) {
                    throw new ErrorException("Parameter 'end' must be a string time in this format (YYYY-MM-DD HH:MM:SS)");
                }
            }

            if (is_int($end) && $end < 0) {
                throw new ErrorException("Parameter 'end' must be a positive integer");
            }
        }
        if (!is_null($index) && !is_int($index)) {
            throw new ErrorException("Parameter 'index' must be an integer");
        }
        if (!is_null($limit) && !is_int($limit)) {
            throw new ErrorException("Parameter 'limit' must be an integer");
        }
        if (!is_null($pending) && (!CommonUtil::is_boolean($pending) || !is_bool($pending))) {
            throw new ErrorException("Parameter 'pending' must be a boolean (1, 0, true, false)");
        }
        if (!is_null($direction)) {
            if (!is_string($direction)) {
                throw new ErrorException("Parameter 'direction' must be string");
            } elseif ($direction !== MessageDirection::IN || $direction !== MessageDirection::OUT) {
                throw new ErrorException("Parameter 'direction' must be string. Possible values are in or out");
            }
        }

        try {
            $params = array();
            if (!is_null($start)) {
                $params["start"] = is_string($start) ? $start : gmdate('Y-m-d H:i:s', $start);
            }
            if (!is_null($end)) {
                $params["end"] = is_string($end) ? $end : gmdate('Y-m-d H:i:s', $end);
            }
            if (!is_null($index)) {
                $params["index"] = $index > 0 ? $index : 0;
            }
            if (!is_null($limit)) {
                $params["limit"] = ($limit > 0 && $limit <= 1000) ? $limit : 1000;
            }
            if (!is_null($pending)) {
                if ($pending === "true" || $pending || $pending === "1") {
                    $params["pending"] = "true";
                }

                if ($pending === "false" || $pending === false || $pending === "0") {
                    $params["pending"] = "false";
                }
            }
            if (!is_null($direction)) {
                $params["direction"] = $direction;
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
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

}
