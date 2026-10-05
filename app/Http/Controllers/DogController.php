<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Http\Requests\IndexDogRequest;
use App\Http\Requests\StoreDogRequest;
use App\Http\Requests\UpdateDogRequest;
use App\Http\Resources\DogResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class DogController extends Controller
{
    public function index(IndexDogRequest $request)
    {
        $per_page = $request->query('per_page', 5);

        $breed = $request->query('breed');

        $sort = $request->query('sort');

        $order = $request->query('order', 'asc');

        $dogs = $request->user()
                        ->dogs()
                        ->when($breed, function ($query, $breed) {
                            $query->where('breed', $breed);
                        })
                        ->when($sort, function ($query, $sort) use ($order) {
                            $query->orderBy($sort, $order);
                        })
                        ->paginate($per_page)
                        ->withQueryString();

        return DogResource::collection($dogs);
    }

    public function store(StoreDogRequest $request)
    {
        $validated = $request->validated();

        $dog = $request->user()->dogs()->create($validated);

        return new DogResource($dog);
    }

    #[Authorize('view', 'dog')]
    public function show(Dog $dog)
    {
        return new DogResource($dog);
    }

    public function update(UpdateDogRequest $request, Dog $dog)
    {
        $validated = $request->validated();

        $dog->update($validated);

        return new DogResource($dog);
    }

    #[Authorize('delete', 'dog')]
    public function destroy(Dog $dog)
    {
        $dog->delete();

        return response()->noContent();
    }
}
