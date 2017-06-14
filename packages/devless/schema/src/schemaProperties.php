<?php

namespace Devless\Schema;

trait schemaProperties
{
    public $db_types = [
        'text' => 'string',
        'textarea' => 'longText',
        'integer' => 'integer',
        'decimals' => 'double',
        'password' => 'string',
        'percentage' => 'integer',
        'url' => 'string',
        'timestamp' => 'timestamp',
        'boolean' => 'boolean',
        'email' => 'string',
        'reference' => 'integer',
        'base64' => 'binary',
    ];

}
