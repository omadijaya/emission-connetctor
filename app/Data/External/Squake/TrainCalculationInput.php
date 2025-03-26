<?php

declare(strict_types=1);

namespace App\Data\External\Squake;

final class TrainCalculationInput
{
    public function __construct(
        public string $methodology,
        public string $external_reference,
        public string $origin,
        public string $destination,
        public int $number_of_travelers,
        public string $train_type,
        public string $fuel_type,
        public string $type = 'train',
    ) {}
}
