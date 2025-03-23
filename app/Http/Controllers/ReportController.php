<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Class1;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::with([
            'class:id',
            'tutor:id,user_id',
            'tutor.user:id,name',
        ])->latest('id')->get();
        return response()->json(
            [
                'success' => true,
                'data' => $reports,
                'message' => 'Reports retrieved successfully'
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
        $request->validate([
            'tutor_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'content' => 'required|string',
        ]);

        try {
            $class = Class1::findOrFail($request->class_id);
            $today = Carbon::now();
            $startDate = Carbon::parse($class->start_date);
            if ($today->lessThan($startDate) && $class->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lớp học chưa được bắt đầu'
                ], 400);
            }

            $report = Report::create([
                'tutor_id' => $request->tutor_id,
                'class_id' => $request->class_id,
                'content' => $request->content,
                'status' => 0,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'data' => $report,
                    'message' => 'Báo cáo lớp học thành công'
                ],
                201
            );
        } catch (Exception $e) {
            Log::error('Unable to report class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi Báo cáo lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $report = Report::with([
            'class:id,parent_id,start_date',
            'class.parent:id,user_id',
            'class.parent.user:id,name,phone,email',
            'tutor:id,user_id',
            'tutor.user:id,name,phone,email',
        ])->find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }

        return response()->json(
            [
                'success' => true,
                'data' => $report,
                'message' => 'Report retrieved successfully'
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Report not found'
                ],
                404
            );
        }

        $request->validate([
            'response' => 'required|string',
        ]);

        try {
            $report->response = $request->response;
            $report->status = 1;
            $report->save();

            return response()->json([
                    'success' => true,
                    'data' => $report,
                    'message' => 'Xử lý báo cáo của gia sư thành công'
                ]);
        } catch (Exception $e) {
            Log::error('Unable to handle report: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi Xử lý báo cáo của gia sư: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
