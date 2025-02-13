<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\Address;
use App\Models\Class1;
use App\Models\ClassSubject;
use App\Models\ClassTime;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Class1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Class1::with([
            'parent:id,user_id',
            'parent.user:id,name',
            'level',
            'subjects',
            'grade',
            'address',
            'tutor',
        ])->latest('id')->get();
        return response()->json(
            [
                'success' => true,
                'data' => $classes,
                'message' => 'Classs retrieved successfully'
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
    public function store(ClassRequest $request)
    {
        $data = $request->all();
        $data['status'] = 0;

        // Bắt đầu transaction
        DB::beginTransaction();
        try {
            $address = Address::create([
                'detail' => $data['detail'],
                'ward_id' => $data['ward_id'],
            ]);
            $data['address_id'] = $address->id;

            $class = new Class1;
            $class->fill($data);
            $class->save();

            $id = $class->id;

            foreach ($data['subjects'] as $subject) {
                ClassSubject::create([
                    'class_id' => $id,
                    'subject_id' => $subject,
                ]);
            };

            foreach ($data['times'] as $time) {
                ClassTime::create([
                    'class_id' => $id,
                    'day' => $time['day'],
                    'start' => $time['start'] ?? null, // Tránh lỗi nếu start không có trong request
                    'end' => $time['end'] ?? null, // Tránh lỗi nếu end không có trong request
                ]);
            };

            // Nếu tất cả thành công, commit transaction
            DB::commit();

            return response()->json(
                [
                    'success' => true,
                    'data' => $class,
                    'message' => 'Tạo lớp học thành công'
                ],
                201
            );
        } catch (Exception $e) {
            // Nếu có lỗi, rollback lại toàn bộ thay đổi
            DB::rollBack();

            Log::error('Unable to create class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tạo lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $class = Class1::with([
            'parent.user:id,name,phone',
            'tutor:id,user_id',
            'tutor.user:id,name,phone',
            'level',
            'address.ward.district',
            'subjects',
            'grade',
            'classTimes'
        ])->find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        return response()->json(
            [
                'success' => true,
                'data' => $class,
                'message' => 'Class retrieved successfully'
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Class1 $class1)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassRequest $request, $id)
    {
        $class = Class1::find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Cập nhật địa chỉ nếu có
            if (!empty($data['detail']) && !empty($data['ward_id'])) {
                $class->address->update([
                    'detail' => $data['detail'],
                    'ward_id' => $data['ward_id']
                ]);
            }

            // Cập nhật thông tin lớp học
            $class->update([
                'num_of_students' => $data['num_of_students'] ?? $class->num_of_students,
                'num_of_sessions' => $data['num_of_sessions'] ?? $class->num_of_sessions,
                'grade_id' => $data['grade_id'] ?? $class->grade_id,
                'gender_tutor' => $data['gender_tutor'] ?? $class->gender_tutor,
                'tuition' => $data['tuition'] ?? $class->tuition,
                'request' => $data['request'] ?? $class->request,
                'status' => $data['status'] ?? $class->status,
                'level_id' => $data['level_id'] ?? $class->level_id,
                'parent_id' => $data['parent_id'] ?? $class->parent_id,
            ]);

            // Cập nhật môn học (subjects)
            if (!empty($data['subjects'])) {
                $class->subjects()->sync($data['subjects']); // Xóa hết & thêm lại
            }

            // Cập nhật lịch học (times)
            if (!empty($data['times'])) {
                // Xóa lịch học cũ
                $class->classTimes()->delete();

                // Thêm lịch học mới
                foreach ($data['times'] as $time) {
                    ClassTime::create([
                        'class_id' => $class->id,
                        'day' => $time['day'],
                        'start' => $time['start'],
                        'end' => $time['end'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $class->load(['address', 'subjects', 'classTimes']),
                'message' => 'Cập nhật lớp học thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật lớp học: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi cập nhật lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $class = Class1::find($id);

        if (!$class) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Class not found'
                ],
                404
            );
        }

        $class->delete(); //Soft delete

        return response()->json(
            [
                'success' => true,
                'message' => 'Class deleted successfully'
            ],
            204
        );
    }
}
