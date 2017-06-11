<?php

namespace Devless\RUlesEngine;

use App\Helpers\Helper;
trait mutateResponse 
{
	/**
     * mutate response status code.
     * @param $newCode
     * @return $this
     */
	public function mutateStatusCode($newCode)
	{
		$this->commonMutationTask();
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
		$this->commonMutationTask();
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
		$this->commonMutationTask();
		$newPayload = (is_array($newPayload))? $newPayload : [$newPayload];

		if (!$this->execOrNot) {
                return $this;
        }
        
        $this->payload = $newPayload;
		return $this;
	}

	private function commonMutationTask()
	{
		($this->request_phase == 'before')? Helper::interrupt(642, "Mutating Response prior to  a query is impossible. If you still wish to end with a custom response use  `->stopAndOutput('status_code', 'message', 'payload')`"): '';
	}
}