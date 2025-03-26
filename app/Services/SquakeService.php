<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\External\Squake\FlightCalculationInput;
use App\Data\External\Squake\HotelCalculationInput;
use App\Data\External\Squake\TrainCalculationInput;
use App\Data\Response\V1\FlightEmissionData;
use App\Data\Response\V1\HotelEmissionData;
use App\Data\Response\V1\TrainEmissionData;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Log;

final class SquakeService
{
    private $baseUrl;

    private $token;

    public function __construct()
    {
        $this->baseUrl = config('squake.base_url');
        $this->token = config('squake.token');
    }

    public function flightEmission($params): Collection
    {
        $normalizeInput = collect($params)->map(function ($item) {
            return new FlightCalculationInput(
                methodology: $item['methodology'] ?? 'ICAO',
                external_reference: $item['item_id'],
                origin: $item['origin'],
                destination: $item['destination'],
                number_of_travelers: $item['number_of_travelers'],
                aircraft_type: $item['aircraft_type'],
                airline: $item['airline'],
                fare_class: $item['fare_class'],
            );
        });

        try {
            $result = $this->getEmissionCalculation($normalizeInput);
        } catch (Exception $e) {
            Log::error('Failed to fetch flight emission calculation', ['exception' => $e]);

            return collect();
        }

        if (empty($result['items'])) {
            return collect();
        }

        return collect($result['items'])->map(function ($item) {
            return new FlightEmissionData(
                $item['external_reference'],
                $item['carbon_quantity'],
                $item['carbon_unit'],
                $item['type'],
                $item['distance'],
                $item['distance_unit'],
                $item['methodology'],
            );
        });
    }

    public function hotelEmission($params): Collection
    {
        $normalizeInput = collect($params)->map(function ($item) {
            return new HotelCalculationInput(
                methodology: $item['methodology'] ?? 'DEFRA',
                external_reference: $item['item_id'],
                country: $item['country'],
                city: $item['city'],
                number_of_nights: $item['number_of_nights'],
                stars: $item['stars'],
            );
        });

        try {
            $result = $this->getEmissionCalculation($normalizeInput);
        } catch (Exception $e) {
            Log::error('Failed to fetch hotel emission calculation', ['exception' => $e]);

            return collect();
        }

        if (empty($result['items'])) {
            return collect();
        }

        return collect($result['items'])->map(function ($item) {
            return new HotelEmissionData(
                $item['external_reference'],
                $item['carbon_quantity'],
                $item['carbon_unit'],
                $item['type'],
                $item['methodology'],
            );
        });
    }

    public function trainEmission($params): Collection
    {
        $normalizeInput = collect($params)->map(function ($item) {
            return new TrainCalculationInput(
                methodology: $item['methodology'] ?? 'DEFRA',
                external_reference: $item['item_id'],
                origin: $item['origin'],
                destination: $item['destination'],
                number_of_travelers: $item['number_of_travelers'],
                train_type: $item['train_type'],
                fuel_type: $item['fuel_type'],
            );
        });

        try {
            $result = $this->getEmissionCalculation($normalizeInput);
        } catch (Exception $e) {
            Log::error('Failed to fetch train emission calculation', ['exception' => $e]);

            return collect();
        }

        if (empty($result['items'])) {
            return collect();
        }

        return collect($result['items'])->map(function ($item) {
            return new TrainEmissionData(
                $item['external_reference'],
                $item['carbon_quantity'],
                $item['carbon_unit'],
                $item['type'],
                $item['distance'],
                $item['distance_unit'],
                $item['methodology'],
            );
        });
    }

    private function getEmissionCalculation($items): array
    {
        $checksum = hash('sha256', json_encode($items->toArray()));
        if (Cache::has('emission_'.$checksum)) {
            return Cache::get('emission_'.$checksum);
        }

        $response = Http::withToken($this->token)
            ->post($this->baseUrl.'/v2/calculations', [
                'expand' => ['items'],
                'items' => $items->toArray(),
            ]);

        if ($response->failed()) {
            throw new Exception('Failed to fetch emission calculation: '.$response->body());
        }

        $result = json_decode($response->getBody()->getContents(), true);

        Cache::put('emission_'.$checksum, $result, now()->addMinutes(10));

        return $result;

    }
}
