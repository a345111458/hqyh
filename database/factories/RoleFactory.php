<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Role;
use Faker\Generator as Faker;


$factory->define(Role::class, function (Faker $faker) {
    $date_time = $faker->date . ' ' . $faker->time;
    return [
        'created_at'    =>  $date_time,
        'updated_at'    =>  $date_time,
    ];
});
