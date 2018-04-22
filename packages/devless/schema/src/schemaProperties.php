<?php

namespace Devless\Schema;

trait schemaProperties
{
    public $db_types = [
        'text' => 'string',
        'textarea' => 'longText',
        'image'=>'longText',
        'integer' => 'integer',
        'decimals' => 'double',
        'password' => 'string',
        'percentage' => 'integer',
        'url' => 'string',
        'timestamp' => 'dateTime',
        'boolean' => 'boolean',
        'email' => 'string',
        'reference' => 'integer',
        'base64' => 'binary',
        'phone_number' => 'string'
    ];
}
