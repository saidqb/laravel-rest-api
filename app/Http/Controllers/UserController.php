<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

class UserController extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA',
        ]);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA',
        ]);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA',
        ]);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA',
        ]);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA',
        ]);
        //
    }
}

