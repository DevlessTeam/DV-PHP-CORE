<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;

trait mutateResponse
{
    /**
     * mutate response status code. This will change the status code that is being outputed eg: `->afterQuering()->mutateStatusCode(1111)`. NB: you should only change the status code if you know what you doing. CHanging it might cause some of the official SDKs to malfunction.
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
     * mutate response message. This will change the Message within the response body sent back to the client. eg `->afterQuering()->mutateResponseMessage("new response message")`
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
     * mutate response payload. This will Replace the Response payload being sent back to the client eg: `->afterQuering()->mutateResponsePayload(["name"=>"Edmond"])`
     * @param $newPayload
     * @return $this
     */
    public function mutateResponsePayload($newPayload)
    {

        $newPayload = (is_array($newPayload))? $newPayload : [$newPayload];
        
        if ((!$this->execOrNot)) {
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
