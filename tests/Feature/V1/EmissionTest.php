<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake([
        'http://your-squake-url/v2/calculations' => Http::response(['data' => 'mocked response'], 200),
    ]);
});

test('it returns 401 for unauthorized access', function () {
    $response = $this->post('/api/v1/emission/flight');

    $response->assertStatus(401);
});

test('it returns 422 for invalid input on emission flight', function () {
    $response = $this->withoutMiddleware()->post('/api/v1/emission/flight');

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'The given data was invalid.',
    ]);
});

test('it returns 200 for valid input on emission flight', function () {
    $response = $this->withoutMiddleware()->post('/api/v1/emission/flight', [
        [
            'item_id' => '1',
            'origin' => 'LHR',
            'destination' => 'JFK',
            'number_of_travelers' => 1,
            'aircraft_type' => 'A380',
            'airline' => 'British Airways',
            'fare_class' => 'Economy',
        ],
    ]);

    $response->assertStatus(200);
});

test('it returns 422 for invalid input on emission hotel', function () {
    $response = $this->withoutMiddleware()->post('/api/v1/emission/hotel');

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'The given data was invalid.',
    ]);
});

test('it returns 200 for valid input on emission hotel', function () {
    $response = $this->withoutMiddleware()->post('/api/v1/emission/hotel', [
        [
            'item_id' => '1',
            'country' => 'ID',
            'city' => 'JKT',
            'number_of_nights' => 1,
            'stars' => 5,
        ],
    ]);

    $response->assertStatus(200);
});

test('it returns 422 for invalid input on emission train', function () {
    $response = $this->withoutMiddleware()->post('/api/v1/emission/train');

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'The given data was invalid.',
    ]);
});

test('it returns 200 for valid input on emission train', function () {
    $response = $this->withoutMiddleware()->post('/api/v1/emission/train', [
        [
            'item_id' => '1',
            'origin' => 'LHR',
            'destination' => 'JFK',
            'number_of_travelers' => 1,
            'train_type' => 'light',
            'fuel_type' => 'diesel',
        ],
    ]);

    $response->assertStatus(200);
});
