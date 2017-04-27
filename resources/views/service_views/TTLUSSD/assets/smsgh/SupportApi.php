<?php

/**
 * Description of SupportApi
 *
 * @author Arsene Tochemey GANDOTE
 */
class SupportApi extends AbstractApi {

    public function __construct($apiHost, $enableConsoleLog = TRUE) {
        parent::__construct($apiHost, $enableConsoleLog);
    }

    /**
     * Gets Support Tickets
     * @param integer $page
     * @param integer $pageSize
     * @return ApiList|HttpResponse|null
     */
    public function getSupportTickets($page = -1, $pageSize = -1) {
        $resource = "/tickets/";
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
     * Gets a given Support Ticket
     * @param integer $ticketId
     * @return HttpResponse|null|Ticket
     * @throws ErrorException
     */
    public function getSupporTicket($ticketId) {
        $resource = "/tickets/";
        if (is_null($ticketId)) {
            throw new ErrorException("Parameter 'ticketId' cannot be null");
        } elseif (!is_int($ticketId)) {
            throw new ErrorException("Parameter 'ticketId' must be an integer");
        }
        try {
            $resource .= $ticketId;
            $response = $this->httpClient->get($resource);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json))
                        return new Ticket($json);
                } else
                    return $response;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return null;
    }

    /**
     * Creates a Support Ticket
     * @param mixed $request
     * @return HttpResponse|null|Ticket
     * @throws ErrorException
     */
    public function addSupportTicket($request) {
        $resource = "/tickets/";
        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!($request instanceof Ticket) || !is_array($request)) {
            throw new ErrorException("Parameter 'request' must be of type Ticket or an array");
        }
        try {
            $params = array();
            $params = is_array($request) ? $request : json_decode(JsonHelper::toJson($request), true);
            $response = $this->httpClient->post($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_CREATED) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Ticket($json);
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
     * Replies to a Support Ticket
     * @param integer $ticketId
     * @param mixed $request
     * @return HttpResponse|null|Ticket
     * @throws ErrorException
     */
    public function updateSupportTicket($ticketId, $request) {
        $resource = "/tickets/";
        if (is_null($ticketId)) {
            throw new ErrorException("Parameter 'ticketId' cannot be null");
        } elseif (!is_int($ticketId)) {
            throw new ErrorException("Parameter 'ticketId' must be an integer");
        }

        if (is_null($request)) {
            throw new ErrorException("Parameter 'request' cannot be null");
        } elseif (!($request instanceof TicketResponse) || !is_array($request)) {
            throw new ErrorException("Parameter 'request' must be of type TicketResponse or an array");
        }

        try {
            $params = array();
            $params = is_array($request) ? $request : json_decode(JsonHelper::toJson($request), true);
            $response = $this->httpClient->put($resource, $params);
            if ($response instanceof HttpResponse) {
                if ($response->getStatus() === HttpStatusCode::HTTP_OK) {
                    $json = JsonHelper::getJson($response->getBody());
                    if (isset($json)) {
                        return new Ticket($json);
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
