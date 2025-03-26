<?php

declare(strict_types=1);

namespace App\Data\External\Squake;

final class FlightCalculationInput
{
    public function __construct(
        public string $methodology,
        public string $external_reference,
        public string $origin,
        public string $destination,
        public int $number_of_travelers,
        public string $aircraft_type,
        public string $airline,
        public string $fare_class,
        public string $type = 'flight',
    ) {}
}
