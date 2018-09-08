<?php

use Faker\Generator as Faker;

$factory->define(App\Merchant::class, function (Faker $faker) {
    return [
        'muid' => $faker->randomLetter,
        'firts_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'business_social_name' => $faker->name,
        'business_social_rfc' => $faker->isbn10,
        'business_social_address' => $faker->streetAddress,
        'business_start' => $faker->time('H:i:s'),
        'business_end' => $faker->time('H:i:s'),
        'approved' => rand(0, 1),
        'status' => rand(0, 1)
    ];
});
