<?php

declare(strict_types=1);

namespace App\Data\External\Squake;

final class HotelCalculationInput
{
    public function __construct(
        public string $methodology,
        public string $external_reference,
        public string $country,
        public string $city,
        public int $number_of_nights,
        public ?int $stars = null,
        public string $type = 'hotel',
    ) {}
}
