<?php

namespace App\Http\Controllers;

use App\Models\Approve;
use App\Models\Class1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class ApproveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($classId)
    {
        $class = Class1::findOrFail($classId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $approve = Approve::where('class_id', $classId)->firstOrFail();
        $this->authorize('viewApprovedTutors', $approve);

        try {
            $approvals = $class->approvals;

            return response()->json([
                'success' => true,
                'data' => $approvals,
                'message' => 'Lấy danh sách gia sư đăng ký lớp thành công'
            ]);
        } catch (Exception $e) {
            Log::error('Unable to get approvals: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy danh sách gia sư đăng ký lớp: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required',
        ]);

        $class_id = $validated['class_id'];
        $class = Class1::find($class_id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $this->authorize('create', [Approve::class, $class]);

        $tutor = Auth::user()->tutor;

        try {
            $isEnroll = Approve::where('class_id', $class_id)
                ->where('tutor_id', $tutor->id)->exists();

            if (!$isEnroll) {
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
                        'message' => 'Bạn đã đăng ký nhận lớp học này rồi'
                    ],
                    404
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $classId)
    {
        $validated = $request->validate([
            'tutor_id' => 'required',
        ]);

        $tutor_id = $validated['tutor_id'];
        $status = $request->status;

        $approval = Approve::where('class_id', $classId)
            ->where('tutor_id', $tutor_id)
            ->first();

        if (!$approval) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xét duyệt gia sư'
            ], 404);
        }

        $this->authorize('update', $approval);

        try {
            $approval->update([
                'status' => $status ?? $approval->status,
            ]);
            return response()->json([
                'success' => true,
                'data' => $approval,
                'message' => 'Xét duyệt thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi xét duyệt gia sư: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi xét duyệt gia sư: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($classId)
    {
        // try {
            $tutor = Auth::user()->tutor;

            $approval = Approve::where('class_id', $classId)
                ->where('tutor_id', $tutor->id)
                ->first();

            if (!$approval) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tutor or class not found'
                ], 404);
            }

            $this->authorize('delete', $approval);

            Approve::where('class_id', $approval->class_id)
                ->where('tutor_id', $approval->tutor_id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Unenrolling class successfully'
            ], 200);
        // } catch (Exception $e) {
        //     Log::error('Unable to unenroll class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Lỗi hủy đăng ký nhận lớp: ' . $e->getMessage()
        //     ], 400);
        // }
    }
}
