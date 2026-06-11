<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    public function isConfigured(): bool
    {
        return filled(config('services.rajaongkir.key')) && (int) config('services.rajaongkir.origin_city_id') > 0;
    }

    public function hasApiKey(): bool
    {
        return filled(config('services.rajaongkir.key'));
    }

    /**
     * @throws RequestException
     */
    public function searchDomesticDestinations(string $search, int $limit = 8): array
    {
        $baseUrl = rtrim(config('services.rajaongkir.base_url'), '/');

        return Http::withHeaders(['key' => config('services.rajaongkir.key')])
            ->get($baseUrl . '/destination/domestic-destination', [
                'search' => $search,
                'limit' => $limit,
                'offset' => 0,
            ])
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     */
    public function cost(int $destination, int $weight, string $courier): array
    {
        $baseUrl = rtrim(config('services.rajaongkir.base_url'), '/');

        return Http::asForm()
            ->withHeaders(['key' => config('services.rajaongkir.key')])
            ->post($baseUrl . '/calculate/domestic-cost', [
                'origin' => (int) config('services.rajaongkir.origin_city_id'),
                'destination' => $destination,
                'weight' => max(1, $weight),
                'courier' => strtolower($courier),
                'price' => 'lowest',
            ])
            ->throw()
            ->json();
    }

    public function cheapestRegularCost(array $response): ?array
    {
        $costs = data_get($response, 'data', []);

        if (! empty($costs)) {
            $selected = collect($costs)
                ->sortBy(fn ($service) => (int) data_get($service, 'cost', PHP_INT_MAX))
                ->first();

            if (! $selected) {
                return null;
            }

            return [
                'service' => $selected['service'] ?? 'REG',
                'description' => $selected['description'] ?? null,
                'cost' => (int) data_get($selected, 'cost', 0),
                'etd' => data_get($selected, 'etd'),
            ];
        }

        $costs = data_get($response, 'rajaongkir.results.0.costs', []);

        if (empty($costs)) {
            return null;
        }

        $selected = collect($costs)
            ->sortBy(fn ($service) => (int) data_get($service, 'cost.0.value', PHP_INT_MAX))
            ->first();

        if (! $selected) {
            return null;
        }

        return [
            'service' => $selected['service'] ?? 'REG',
            'description' => $selected['description'] ?? null,
            'cost' => (int) data_get($selected, 'cost.0.value', 0),
            'etd' => data_get($selected, 'cost.0.etd'),
        ];
    }
}
