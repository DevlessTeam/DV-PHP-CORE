<?php

namespace Devless\RUlesEngine;

trait mutateResponse 
{
	public function mutateResponseStatusCode($newCode)
	{
		if (!$this->execOrNot) {
                return $this;
        }

		$this->status_code = $newCode;
		return $this;
	}

	public function mutateResponseMessage($newMessage)
	{
		if (!$this->execOrNot) {
                return $this;
        }
        
        $this->message = $newMessage;
		return $this;
	}

	public function mutateResponsePayload($newPayload)
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->payload = $newPayload;
		return $this;
	}	
}