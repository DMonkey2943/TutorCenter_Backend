<?php

namespace App\Http\Controllers;

use App\Models\Class1;
use App\Models\Rate;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        try {
            $class = Class1::findOrFail($request->class_id);
            if ($class->end_date == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lớp học chưa kết thúc'
                ], 400);
            }

            $this->authorize('create', [Rate::class, $class]);

            $rate = Rate::create([
                'stars' => $request->stars,
                'comment' => $request->comment,
                'tutor_id' => $request->tutor_id,
                'parent_id' => $request->parent_id,
                'class_id' => $request->class_id,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'data' => $rate,
                    'message' => 'Đánh giá gia sư thành công'
                ],
                201
            );
        } catch (Exception $e) {
            Log::error('Unable to rating tutor: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi đánh giá gia sư: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($classId)
    {
        $class = Class1::findOrFail($classId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $this->authorize('view', $class);

        try {
            $rate = $class->rate;

            return response()->json([
                'success' => true,
                'data' => $rate,
                'message' => 'Lấy đánh giá gia sư thành công'
            ]);
        } catch (Exception $e) {
            Log::error('Unable to get rating tutor: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy đánh giá gia sư: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
