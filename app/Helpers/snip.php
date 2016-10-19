<?php


function getParam($EVENT, $field)
{
    return $EVENT['params'][0]['field'][0][$field];
}
