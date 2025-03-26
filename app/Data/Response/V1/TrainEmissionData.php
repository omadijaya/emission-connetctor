<?php

declare(strict_types=1);

namespace App\Data\Response\V1;

final class TrainEmissionData
{
    public function __construct(
        public string $item_id,
        public int $carbon_quantity,
        public string $carbon_unit,
        public string $type,
        public float $distance,
        public string $distance_unit,
        public string $methodology,
    ) {}
}
