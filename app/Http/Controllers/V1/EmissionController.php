<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\FlightEmissionRequest;
use App\Http\Requests\V1\HotelEmissionRequest;
use App\Http\Requests\V1\TrainEmissionRequest;
use App\Services\SquakeService;

final class EmissionController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse of App\Data\Response\V1\FlightEmissionData[]
     */
    public function getFlightEmission(FlightEmissionRequest $request, SquakeService $squakeService)
    {

        $emission = $squakeService->flightEmission($request->all());

        return response()->json($emission);
    }

    /**
     * @return \Illuminate\Http\JsonResponse of App\Data\Response\V1\HotelEmissionData[]
     */
    public function getHotelEmission(HotelEmissionRequest $request, SquakeService $squakeService)
    {

        $emission = $squakeService->hotelEmission($request->all());

        return response()->json($emission);
    }

    /**
     * @return \Illuminate\Http\JsonResponse of App\Data\Response\V1\TrainEmissionData[]
     */
    public function getTrainEmission(TrainEmissionRequest $request, SquakeService $squakeService)
    {

        $emission = $squakeService->trainEmission($request->all());

        return response()->json($emission);
    }
}
