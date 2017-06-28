<?php

namespace Devless\RulesEngine;

trait fillers
{

    /**
     * `also` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes. eg: `->beforeCreating()->assign("hello")->to($text)->convertToUpperCase()->reverseString()->storeAs($text)->succeedWith($text)` this can be rewritten as `beforeCreating()->assign("hello")->to($text)->convertToUpperCase()->also->reverseString()->storeAs($text)->succeedWith($text)` notice the introduction of the `also` keyword, this does not change the end results but just makes it more readable.
     *
     * @return $this
     */
    public function also()
    {
        return $this;
    }

    /**
     * `then` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes. eg: `->beforeCreating()->assign("hello")->to($text)->convertToUpperCase()->reverseString()->storeAs($text)->succeedWith($text)` this can be rewritten as `beforeCreating()->assign("hello")->to($text)->convertToUpperCase()->then->reverseString()->storeAs($text)->succeedWith($text)` notice the introduction of the `then` keyword, this does not change the end results but just makes it more readable.
     *
     * @return $this
     */
    public function then()
    {
        return $this;
    }

    /**
     * `firstly` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes eg: `->beforeCreating()->firstly->assign("hello")->to($text)->secondly->convertToUpperCase()->then->storeAs($text)->lastly->succeedWith($text)`.
     *
     * @return $this
     */
    public function firstly()
    {
        return $this;
    }

    /**
     * `secondly` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes eg: `->beforeCreating()->firstly->assign("hello")->to($text)->secondly->convertToUpperCase()->then->storeAs($text)->lastly->succeedWith($text)`.
     *
     * @return $this
     */
    public function secondly()
    {
        return $this;
    }

    /**
     * `thirdly` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes eg: `->beforeCreating()->firstly->assign("hello")->to($text)->secondly->convertToUpperCase()->then->storeAs($text)->lastly->succeedWith($text)`.
     *
     * @return $this
     */
    public function thirdly()
    {
        return $this;
    }

    /**
     * `lastly` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes eg: `->beforeCreating()->firstly->assign("hello")->to($text)->secondly->convertToUpperCase()->then->storeAs($text)->lastly->succeedWith($text)`.
     *
     * @return $this
     */
    public function lastly()
    {
        return $this;
    }

    /**
     * `beSureTo` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes eg: `->beforeCreating()->firstly->assign("hello")->to($text)->secondly->convertToUpperCase()->then->beSureTo->storeAs($text)->lastly->succeedWith($text)`.
     *
     * @return $this
     */
    public function beSureTo()
    {
        return $this;
    }

    /**
     * `next` filler word like all other filler words does not come with any side effects but is used sorely for readability purposes eg: `->beforeCreating()->firstly->assign("hello")->to($text)->secondly->convertToUpperCase()->next->storeAs($text)->lastly->succeedWith($text)`.
     *
     * @return $this
     */
    public function next()
    {
        return $this;
    }
}
