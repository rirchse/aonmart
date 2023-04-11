<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Models\District;
use App\Models\Division;
use App\Models\Upazila;
use Illuminate\Http\JsonResponse;

class GeoDataController extends ApiController
{
    public function getDivisions(): JsonResponse
    {
        $divisions = Division::get([
            'id', 'name', 'bn_name', 'lat', 'long'
        ]);
        return apiResponse(
            200,
            'Division data successfully fetched.',
            $divisions
        );
    }

    public function getDistricts(): JsonResponse
    {
        $division_id = request()->get('division_id', FALSE);
        $districts = District::when($division_id, fn($query) => $query->where('division_id', $division_id))->get([
            'id', 'division_id', 'name', 'bn_name', 'lat', 'long'
        ]);
        return apiResponse(
            200,
            'Districts data successfully fetched.',
            $districts
        );
    }

    public function getUpazilas(): JsonResponse
    {
        $district_id = request()->get('district_id', FALSE);
        $upazilas = Upazila::when($district_id, fn($query) => $query->where('district_id', $district_id))->get([
            'id', 'district_id', 'name', 'bn_name'
        ]);
        return apiResponse(
            200,
            'Upazilas data successfully fetched.',
            $upazilas
        );
    }
}
