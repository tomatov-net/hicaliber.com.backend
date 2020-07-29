<?php

namespace App\Http\Controllers;

use App\House;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = House::select('*');

            $searchStrict = [
                'bedrooms',
                'bathrooms',
                'storeys',
                'garages',
            ];

            if ($name = $request->input('name', null)) {
                $data = $data->where('name', 'like', "%{$name}%");
            }

            foreach ($searchStrict as $item) {
                if ($search = $request->input($item, null)) {
                    $data = $data->where($item, $search);
                }
            }

            if ($priceMin = $request->input('price_min', null)) {
                $data = $data->where('price', '>=', $priceMin);
            }

            if ($priceMax = $request->input('price_max', null)) {
                $data = $data->where('price', '<=', $priceMax);
            }

            return response()->json([
                'records' => $data->get(), //here should be pagination instead, but for demo project I'll skip it
            ]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
