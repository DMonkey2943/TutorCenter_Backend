<?php

namespace App\Http\Controllers;

use App\Models\Tuition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TuitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tuitions = DB::table("tuitions")
            ->select(
                "id as value",
                "range as label"
            )
            ->get();
        return response()->json(
            [
                'success' => true,
                'data' => $tuitions,
                'message' => 'Tuitions retrieved successfully'
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tuition $tuition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tuition $tuition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tuition $tuition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tuition $tuition)
    {
        //
    }
}
