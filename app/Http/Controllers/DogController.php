<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Http\Requests\StoreDogRequest;
use App\Http\Requests\UpdateDogRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class DogController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 5);

        $breed = $request->query('breed');

        $dogs = $request->user()
                        ->dogs()
                        ->when($breed, function ($query, $breed) {
                            $query->where('breed', $breed);
                        })
                        ->paginate($per_page)
                        ->withQueryString();

        return $dogs;
    }

    public function store(StoreDogRequest $request)
    {
        $validated = $request->validated();

        $dog = $request->user()->dogs()->create($validated);

        return response()->json($dog, 201);
    }

    #[Authorize('view', 'dog')]
    public function show(Dog $dog)
    {
        return $dog;
    }

    public function update(UpdateDogRequest $request, Dog $dog)
    {
        $validated = $request->validated();

        $dog->update($validated);

        return response()->json($dog);
    }

    #[Authorize('delete', 'dog')]
    public function destroy(Dog $dog)
    {
        $dog->delete();

        return response()->noContent();
    }
}
