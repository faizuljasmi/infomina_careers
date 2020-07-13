<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vacancy;
use Faker\Generator as Faker;

$factory->define(Vacancy::class, function (Faker $faker) {
    return [
        'job_title' => $faker->jobTitle,
        'job_desc' => $faker->text($maxNbChars = 200),
        'job_req' => $faker->text($maxNbChars =500),
        'location' => $faker->city,
        'job_type' => $faker->randomElement(['full-time' ,'contract', 'internship']),
    ];
});
