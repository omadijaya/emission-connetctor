<?php

declare(strict_types=1);

namespace App\Data\Response\V1;

final class HotelEmissionData
{
    public function __construct(
        public string $item_id,
        public int $carbon_quantity,
        public string $carbon_unit,
        public string $type,
        public string $methodology,
    ) {}
}
