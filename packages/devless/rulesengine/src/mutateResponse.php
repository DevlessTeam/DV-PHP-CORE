<?php

namespace Devless\RUlesEngine;

trait mutateResponse 
{
	/**
     * mutate response status code.
     * @param $newCode
     * @return $this
     */
	public function mutateStatusCode($newCode)
	{
		if (!$this->execOrNot) {
                return $this;
        }

		$this->status_code = $newCode;
		return $this;
	}

	/**
     * mutate response message.
     * @param $newMessage
     * @return $this
     */
	public function mutateResponseMessage($newMessage)
	{
		if (!$this->execOrNot) {
                return $this;
        }

        $this->message = $newMessage;
		return $this;
	}

	/**
     * mutate response payload.
     * @param $newPayload
     * @return $this
     */
	public function mutateResponsePayload($newPayload)
	{
		$newPayload = (is_array($newPayload))? $newPayload : [$newPayload];
		if (!$this->execOrNot) {
                return $this;
        }
        $this->payload = $newPayload;
		return $this;
	}	
}