<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class DogController extends Controller
{
    public function index(Request $request): Collection
    {
        return $request->user()->dogs()->get();
    }

    public function store(Request $request)
    {
        //
    }

    #[Authorize('view', 'dog')]
    public function show(Dog $dog)
    {
        return $dog;
    }

    public function update(Request $request, Dog $dog)
    {
        //
    }

    public function destroy(Dog $dog)
    {
        //
    }
}
