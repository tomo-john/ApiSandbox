<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Walk;
use App\Http\Requests\StoreWalkRequest;
use App\Http\Requests\UpdateWalkRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class WalkController extends Controller
{
    #[Authorize('view', 'dog')]
    public function index(Dog $dog): Collection
    {
        return $dog->walks()->get();
    }

    public function store(StoreWalkRequest $request, Dog $dog)
    {
        $validated = $request->validated();

        $walk = $dog->walks()->create($validated);

        return response()->json($walk, 201);
    }

    #[Authorize('view', 'walk')]
    public function show(Dog $dog, Walk $walk)
    {
        return $walk;
    }

    public function update(UpdateWalkRequest $request, Dog $dog, Walk $walk)
    {
        $validated = $request->validated();

        $walk->update($validated);

        return response()->json($walk);
    }

    #[Authorize('delete', 'walk')]
    public function destroy(Dog $dog, Walk $walk)
    {
        $walk->delete();

        return response()->noContent();
    }
}
