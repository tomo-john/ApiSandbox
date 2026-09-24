<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

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
