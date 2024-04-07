<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMuseumRequest;
use App\Http\Requests\UpdateMuseumRequest;
use App\Models\Museum;
use Illuminate\Http\Request;

class MuseumController extends Controller
{
    public function index()
    {
        $museums = Museum::all();

        return response()->json([
            'data' => $museums
        ]);
    }

    public function store(StoreMuseumRequest $request)
    {
        $museum = Museum::create($request->validated());

        return response()->json([
            'message' => 'Museum created successfully',
            'data' => $museum
        ], 201);
    }

    public function update(UpdateMuseumRequest $request, Museum $museum)
    {
        $museum->update($request->validated());

        return response()->json([
            'message' => 'Museum updated successfully',
            'data' => $museum
        ]);
    }

    public function destroy(Museum $museum)
    {
        $museum->delete();

        return response()->json([
            'message' => 'Museum deleted successfully'
        ]);
    }
}
