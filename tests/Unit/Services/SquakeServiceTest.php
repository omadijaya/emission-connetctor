<?php

declare(strict_types=1);

use App\Data\Response\V1\FlightEmissionData;
use App\Data\Response\V1\HotelEmissionData;
use App\Data\Response\V1\TrainEmissionData;
use App\Services\SquakeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

test('calculates flight emissions', function () {
    Http::fake([
        'http://your-squake-url/*' => Http::response([
            'items' => [
                [
                    'external_reference' => '0cd5fb23-65db-427a-a6ec-3d37897c5f35',
                    'carbon_quantity' => 1234,
                    'carbon_unit' => 'kg',
                    'type' => 'flight',
                    'distance' => 1000.0,
                    'distance_unit' => 'km',
                    'methodology' => 'ICAO',
                ],
            ],
        ]),
    ]);

    $params = [
        [
            'item_id' => '0cd5fb23-65db-427a-a6ec-3d37897c5f35',
            'origin' => 'Paris',
            'destination' => 'London',
            'number_of_travelers' => 1,
            'aircraft_type' => 'A380',
            'airline' => 'British Airways',
            'fare_class' => 'Economy',
        ],
    ];

    $service = new SquakeService();
    $result = $service->flightEmission($params);

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->count())->toBe(1);
    expect($result->first())->toBeInstanceOf(FlightEmissionData::class);
    expect($result->first()->item_id)->toBe('0cd5fb23-65db-427a-a6ec-3d37897c5f35');
    expect($result->first()->carbon_quantity)->toBe(1234);
    expect($result->first()->carbon_unit)->toBe('kg');
    expect($result->first()->type)->toBe('flight');
    expect($result->first()->distance)->toBe(1000.0);
    expect($result->first()->distance_unit)->toBe('km');
    expect($result->first()->methodology)->toBe('ICAO');
});

test('calculates hotel emissions', function () {
    Http::fake([
        'http://your-squake-url/*' => Http::response([
            'items' => [
                [
                    'external_reference' => '7369861a-ca0c-49e1-9f01-139aa8da830b',
                    'carbon_quantity' => 1234,
                    'carbon_unit' => 'kg',
                    'type' => 'hotel',
                    'methodology' => 'DEFRA',
                ],
            ],
        ]),
    ]);

    $params = [
        [
            'item_id' => '7369861a-ca0c-49e1-9f01-139aa8da830b',
            'country' => 'ID',
            'city' => 'JKT',
            'stars' => 4,
            'number_of_nights' => 3,
        ],
    ];

    $service = new SquakeService();
    $result = $service->hotelEmission($params);

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->count())->toBe(1);
    expect($result->first())->toBeInstanceOf(HotelEmissionData::class);
    expect($result->first()->item_id)->toBe('7369861a-ca0c-49e1-9f01-139aa8da830b');
    expect($result->first()->carbon_quantity)->toBe(1234);
    expect($result->first()->carbon_unit)->toBe('kg');
    expect($result->first()->type)->toBe('hotel');
    expect($result->first()->methodology)->toBe('DEFRA');
});

test('calculates train emissions', function () {
    Http::fake([
        'http://your-squake-url/*' => Http::response([
            'items' => [
                [
                    'external_reference' => '42737b25-39a4-48d3-a6f9-857d0accd633',
                    'carbon_quantity' => 1234,
                    'carbon_unit' => 'kg',
                    'type' => 'train',
                    'distance' => 1000.1,
                    'distance_unit' => 'km',
                    'methodology' => 'ICAO',
                ],
            ],
        ]),
    ]);

    $params = [
        [
            'item_id' => '42737b25-39a4-48d3-a6f9-857d0accd633',
            'origin' => 'Paris',
            'destination' => 'London',
            'number_of_travelers' => 1,
            'train_type' => 'light',
            'fuel_type' => 'diesel',
        ],
    ];

    $service = new SquakeService();
    $result = $service->trainEmission($params);

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->count())->toBe(1);
    expect($result->first())->toBeInstanceOf(TrainEmissionData::class);
    expect($result->first()->item_id)->toBe('42737b25-39a4-48d3-a6f9-857d0accd633');
    expect($result->first()->carbon_quantity)->toBe(1234);
    expect($result->first()->carbon_unit)->toBe('kg');
    expect($result->first()->type)->toBe('train');
    expect($result->first()->distance)->toBe(1000.1);
    expect($result->first()->distance_unit)->toBe('km');
    expect($result->first()->methodology)->toBe('ICAO');
});
