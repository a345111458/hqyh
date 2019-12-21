<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Bonus;
use Faker\Generator as Faker;


$factory->define(Bonus::class, function (Faker $faker) {
    $data = date('Y-m-d H:i:s' , time());
    return [
        'created_at'    =>  $data,
    ];
});
