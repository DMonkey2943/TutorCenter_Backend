<?php

namespace App\Http\Controllers;

use App\Models\Approve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class ApproveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tutor = Auth::user()->tutor;
        $class_id = $request->class_id;

        try {
            $isEnroll = Approve::where('class_id', $class_id)
            ->where('tutor_id', $tutor->id)->exists(); 

            if(!$isEnroll) {
                $approve = Approve::create([
                    'class_id' => $class_id,
                    'tutor_id' => $tutor->id,
                    'status' => 0,
                ]);
    
                return response()->json(
                    [
                        'success' => true,
                        'data' => $approve,
                        'message' => 'Enrolling class successfully'
                    ]
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'You have enrolled the class'
                    ]
                );
            }
        } catch (Exception $e) {
            Log::error('Unable to enroll class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi đăng ký nhận lớp: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Approve $approve)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approve $approve)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approve $approve)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approve $approve)
    {
        //
    }
}
