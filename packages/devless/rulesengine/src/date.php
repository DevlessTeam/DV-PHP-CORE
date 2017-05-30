<?php

namespace Devless\RulesEngine;

trait date 
{
	/**
     * get timestamp.
     *
     *
     * @return $this
     */
	public function getTimestamp()
	{
		if (!$this->execOrNot) {
                return $this;
        }

        $date = new \DateTime();
		$this->results = $date->getTimestamp();
        return $this;
	}

	/**
     * get current year.
     *
     * @return $this
     */
	public function getCurrentYear()
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->results = date('Y');
        return $this;
	}

	/**
     * get current month.
     *
     * @return $this
     */
	public function getCurrentMonth()
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->results = date('M');
        return $this;
	}

	/**
     * get current day.
     *
     * @return $this
     */
	public function getCurrentDay()
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->results = date('D');
        return $this;
	}

	/**
     * get current hour.
     *
     * @return $this
     */
	public function getCurrentHour()
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->results = date('h');
        return $this;
	}

	/**
     * get current minute.
     *
     * @return $this
     */
	public function getCurrentMinute()
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->results = date('i');
        return $this;
	}

	/**
     * get current second.
     *
     * @return $this
     */
	public function getCurrentSecond()
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->results = date('s');
        return $this;
	}

	/**
     * get current datetime.
     *
     * @return $this
     */
	public function getFormatedDate()
	{
		if (!$this->execOrNot) {
                return $this;
        }
        $this->results = date('l jS \of F Y h:i:s A');
        return $this;
	}
}