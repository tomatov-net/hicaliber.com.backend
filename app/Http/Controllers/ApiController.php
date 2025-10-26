<?php

namespace App\Http\Controllers;

use App\House;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Search houses by various criteria
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'storeys' => 'nullable|integer|min:0',
            'garages' => 'nullable|integer|min:0',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
        ]);

        $query = House::query();

        $this->applyFilters($query, $validated);

        return response()->json([
            'records' => $query->get(),
        ]);
    }

    /**
     * Apply search filters to the query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    private function applyFilters($query, array $filters): void
    {
        // Search by name (partial match)
        $query->when($filters['name'] ?? null, function ($q, $name) {
            return $q->where('name', 'like', "%{$name}%");
        });

        // Exact match filters
        $exactMatchFields = ['bedrooms', 'bathrooms', 'storeys', 'garages'];
        foreach ($exactMatchFields as $field) {
            $query->when($filters[$field] ?? null, function ($q, $value) use ($field) {
                return $q->where($field, $value);
            });
        }

        // Price range filters
        $query->when($filters['price_min'] ?? null, function ($q, $priceMin) {
            return $q->where('price', '>=', $priceMin);
        });

        $query->when($filters['price_max'] ?? null, function ($q, $priceMax) {
            return $q->where('price', '<=', $priceMax);
        });
    }
}
