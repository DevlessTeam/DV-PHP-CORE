<?php

namespace Devless\RulesEngine;

trait generators
{
    /**
     * generates random integers. This may be used for invitation or promotional code generation. eg `->beforeCreating()->generateRandomInteger($max=10)->storeAs($promo_code)->succeedWith($promo_code) #7`
     * @param $length
     * @return $this
     */
    public function generateRandomInteger($length = 100000000000000000000)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = rand(0, $length);
        $this->cleanOutput();
        return $this;
    }

    /**
     * generates random alphanumeric values. This may be used for generating order Ids. eg `->beforeCreating()->generateRandomAlphanums($length = 5)->storeAs($order_id)->succeedWith($order_id) #QJXIS`
     * @return $this
     */
    public function generateRandomAlphanums($length = 5)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $this->results = $randomString;
        $this->cleanOutput();
        return $this;
    }

    /**
     * generates random string.This generates random string codes. eg `->beforeCreating()->generateRandomString($length = 10)->storeAs($promo_code)->succeedWith($promo_code) #vXJNKuBaWK`
     * @param $length
     * @return $this
     */
    public function generateRandomString($length = 10)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $this->results = $randomString;
        $this->cleanOutput();
        return $this;
    }

    /**
     *generates unique Id.This generates unique Id . eg `->beforeCreating()->generateUniqueId()->storeAs($user_id)->succeedWith($user_id) #5a48b44ca776c`
     * @param $length
     * @return $this
     */
    public function generateUniqueId()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = uniqid();
        $this->cleanOutput();
        return $this;
    }
}
