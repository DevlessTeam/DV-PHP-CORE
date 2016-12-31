<?php
/**
 * Created by PhpStorm.
 * User: ntimobedyeboah
 * Date: 12/13/16
 * Time: 10:46 PM
 */

$factory->define(\App\App::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'token' => md5(uniqid(1, true))
    ];
});