<?php

namespace Devless\RulesEngine;

trait fillers
{

    /**
     * also filler statement.
     *
     * @return instance
     */
    public function also()
    {
        return $this;
    }

    /**
     * then filler statement.
     *
     * @return instance
     */
    public function then()
    {
        return $this;
    }

    /**
     * firstly filler statement.
     *
     * @return instance
     */
    public function firstly()
    {
        return $this;
    }

    /**
     * secondly filler statement.
     *
     * @return instance
     */
    public function secondly()
    {
        return $this;
    }

    /**
     * thirdly filler statement.
     *
     * @return instance
     */
    public function thirdly()
    {
        return $this;
    }
    /**
     * finally filler statement.
     *
     * @return instance
     */
    public function lastly()
    {
        return $this;
    }

    /**
     * beSureTO filler statement.
     *
     * @return instance
     */
    public function beSureTO()
    {
        return $this;
    }
}
