<?php

namespace App\Modules\InstrumentProjection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\InstrumentProjection\Models\PublishedInstrument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstrumentProjectionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        abort_unless(hash_equals((string) config('maracuja.cremona.projection_token'), (string) $request->bearerToken()), 403);
        $data = $request->validate(['id' => ['required', 'integer'], 'reference' => ['nullable', 'string', 'max:80'], 'slug' => ['nullable', 'string', 'max:255'], 'name' => ['required', 'string', 'max:255'], 'family' => ['nullable', 'string', 'max:80'], 'maker' => ['nullable', 'string', 'max:255'], 'description' => ['nullable', 'string'], 'price_label' => ['nullable', 'string', 'max:255'], 'sale_amount' => ['nullable', 'numeric'], 'rental_amount' => ['nullable', 'numeric'], 'availability' => ['required', 'string', 'in:available,reserved,rented,in_workshop,sold,archived'], 'published_at' => ['required', 'date']]);
        PublishedInstrument::query()->updateOrCreate(['cremona_id' => $data['id']], $data);

        return response()->json(['data' => ['id' => $data['id']]], 201);
    }
}
