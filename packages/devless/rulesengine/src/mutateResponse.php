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
		if (!$this->execOrNot) {
                return $this;
        }
		$this->commonMutationTask();
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
		$this->commonMutationTask();
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
        
		$this->commonMutationTask();
        $this->payload = $newPayload;
		return $this;
	}

	/**
     * execute common mutation tasks.
     */
	private function commonMutationTask()
	{	
		
		($this->request_phase == 'before')? Helper::interrupt(642, "Mutating Response prior to a query is impossible. If you still wish to end with a custom response use  `->stopAndOutput('status_code', 'message', 'payload')`"): '';
	}
}