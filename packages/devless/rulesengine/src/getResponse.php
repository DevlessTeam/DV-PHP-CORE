<?php

namespace DevLess\RulesEngine;

trait getResponse
{


    /**
     * get the output response message. This will fetch the Message about to be sent back to the client . eg `->afterQuering()->getResponse($status_code, $message, $payload)` now the variable $status_code, $message, $payload will be available to use within Rules.
     * @param $status_code
     * @param $message
     * @param $payload
     * @return $this
     */
    public function getResponse(&$status_code, &$message, &$payload)
    {
        if (!$this->execOrNot) {
                return $this;
        }
        $this->commonMutationTask();
        $status_code = $this->status_code;
        $message = $this->message;
        $payload = $this->payload;
        return $this;
    }

    /**
     * get the output status code. This will fetch the status code about to be sent back to the client . eg `->afterQuering()->getStatusCode()->storeAs($status_code)` now the variable $status_code will be available to use within Rules.
     * @param $status_code
     * @return $this
     */
    public function getStatusCode(&$status_code = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->commonMutationTask();
        if (count(func_get_args()) >= 1) {
            $status_code = $this->status_code;
            return $this;
        }
        $this->results = $this->status_code;
        $this->cleanOutput();
        return $this;
    }

    /**
     * get the output message. This will fetch the message about to be sent back to the client . eg `->afterQuering()->getResponseMessage()->storeAs($message)` now the variable $message will be available to use within Rules.
     * @param $message
     * @return $this
     */
    public function getResponseMessage(&$message = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->commonMutationTask();
        if (count(func_get_args()) >= 1) {
            $message = $this->message;
            return $this;
        }
        $this->results = $this->message;
        $this->cleanOutput();
        return $this;
    }

    /**
     * get the output payload. This will fetch the payload about to be sent back to the client . eg `->afterQuering()->getResponsePayload()->storeAs($payload)` now the variable $paylaod will be available to use within Rules.
     * @param $payload
     * @return $this
     */
    public function getResponsePayload(&$payload = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }

        $this->commonMutationTask();
        if (count(func_get_args()) >= 1) {
            $payload = $this->payload;
            return $this;
        }
        if (isset($this->payload['results'])) {
            $this->results = $this->payload['results'];
        } else {
            $this->results = $this->payload;
        }
        $this->cleanOutput();
        return $this;
    }
}
