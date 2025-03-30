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
use Illuminate\Support\Facades\Auth;
use App\Models\Approve;
use App\Mail\TutorAcceptedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class Class1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Class1::class);

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
     * Store a newly created resource in storage.
     */
    public function store(ClassRequest $request)
    {
        $this->authorize('create', Class1::class);

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

            // Kiểm tra và xử lý danh sách tutors nếu phụ huynh có chọn gia sư
            if (isset($data['tutors']) && !empty($data['tutors'])) {
                foreach ($data['tutors'] as $tutor_id) {
                    Approve::create([
                        'class_id' => $id,
                        'tutor_id' => $tutor_id,
                        'status' => 2,
                    ]);
                }
            }

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
            'classTimes',
            // 'approvals:tutor_id,class_id,status'
        ])->find($id);

        $this->authorize('view', $class);

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
     * Update the specified resource in storage.
     */
    public function update(ClassRequest $request, $id)
    {
        $class = Class1::find($id);

        $this->authorize('update', $class);

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
                'start_date' => $data['start_date'] ?? $class->start_date,
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

        $this->authorize('delete', $class);

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

    public function get12Classes()
    {
        $classes = Class1::with([
            'level',
            'subjects',
            'grade',
            'address:id,ward_id',
            'address.ward:id,name,district_id',
            'address.ward.district:id,name',
            'classTimes'
        ])
            ->where('status', 0)
            ->latest()
            ->take(12) // Lấy 12 bản ghi
            ->get()->makeHidden([
                'parent_id',
                'end_date',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        return response()->json(
            [
                'success' => true,
                'data' => $classes,
                'message' => '12 classes retrieved successfully'
            ]
        );
    }

    public function getAllNewClasses(Request $request)
    {
        $query = Class1::with([
            'level',
            'subjects',
            'grade',
            'address:id,ward_id',
            'address.ward:id,name,district_id',
            'address.ward.district:id,name',
            'classTimes',
        ])->where('status', 0);

        // Nếu người dùng đã đăng nhập và có tutor, lấy thêm approvals
        // dd(Auth::check());
        // if (Auth::check() && Auth::user()->tutor) {
        //     $tutor = Auth::user()->tutor;
        //     $query->with(['approvals' => function ($query) use ($tutor) {
        //         $query->where('tutor_id', $tutor->id)
        //             ->select('tutor_id', 'class_id', 'status');
        //     }]);
        // }

        $classes = $query->latest()->paginate(12);

        // Áp dụng makeHidden cho từng model trong collection
        $classes->getCollection()->transform(function ($class) {
            return $class->makeHidden([
                'parent_id',
                'end_date',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        });

        return response()->json($classes);
    }

    public function getEnrolledClasses(Request $request) //for tutors
    {
        $this->authorize('isTutor', Class1::class);

        $tutor = Auth::user()->tutor;

        // if (!$tutor) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Gia sư không hợp lệ'
        //     ], 400);
        // }

        try {
            $classes = Class1::whereHas('approvals', function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id);
            })->with([
                'level',
                'subjects',
                'grade',
                'address:id,ward_id',
                'address.ward:id,name,district_id',
                'address.ward.district:id,name',
                'classTimes',
                'approvals' => function ($query) use ($tutor) {
                    $query->where('tutor_id', $tutor->id)->select('tutor_id', 'class_id', 'status');
                }
            ])->latest()->paginate(12);

            $classes->getCollection()->transform(function ($class) {
                return $class->makeHidden([
                    'parent_id',
                    'end_date',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]);
            });

            return response()->json($classes);
        } catch (Exception $e) {
            Log::error('Unable to enroll class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi truy xuất lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    public function getConfirmedClasses() //for tutors
    {
        $this->authorize('isTutor', Class1::class);

        $tutor = Auth::user()->tutor;

        // if (!$tutor) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Gia sư không hợp lệ'
        //     ], 400);
        // }

        try {
            $classes = Class1::whereHas('approvals', function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id)
                    ->where('status', 1);
            })->with([
                'level',
                'subjects',
                'grade',
                'address:id,ward_id',
                'address.ward:id,name,district_id',
                'address.ward.district:id,name',
                'classTimes',
                'approvals' => function ($query) use ($tutor) {
                    $query->where('tutor_id', $tutor->id)->select('tutor_id', 'class_id', 'status');
                }
            ])->latest()->paginate(6);

            return response()->json($classes);
        } catch (Exception $e) {
            Log::error('Unable to enroll class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi truy xuất lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    public function confirmClassTeaching($classId)
    { //for tutors
        $tutor = Auth::user()->tutor;
        // if (!$tutor) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Bạn không phải là gia sư!'
        //     ], 403);
        // }

        // Kiểm tra xem gia sư đã được duyệt chưa
        // $approved = Approve::where('class_id', $classId)
        //     ->where('tutor_id', $tutor->id)
        //     ->whereIn('status', [1, 2])
        //     ->exists();
        // if (!$approved) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Bạn chưa được duyệt để dạy lớp này!'
        //     ], 403);
        // }

        // Tìm lớp học
        $class = Class1::with([
            'grade',
            'subjects',
            'classTimes',
            'address.ward.district',
        ])->find($classId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Lớp học không tồn tại!'
            ], 404);
        }

        $this->authorize('confirmTeaching', $class);

        // Kiểm tra xem lớp đã có gia sư nhận dạy chưa
        // if ($class->status == 1) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Lớp học này đã có gia sư nhận dạy!'
        //     ], 400);
        // }

        try {
            $class->update([
                'status' => 1,
                'tutor_id' => $tutor->id
            ]);

            $tutorInfo = $tutor->user;

            $parent = $class->parent->user;
            $parentInfo = [
                'name' => $parent->name,
                'phone' => $parent->phone,
            ];

            // Xây dựng địa chỉ hoàn chỉnh
            $addressDetail = optional($class->address)->detail ?? 'N/A';
            $wardName = optional($class->address->ward)->name ?? 'N/A';
            $districtName = optional($class->address->ward->district)->name ?? 'N/A';
            $fullAddress = "{$addressDetail}, {$wardName}, {$districtName}";

            $classInfo = [
                'id' => $class->id,
                'grade' => $class->grade->name ?? 'N/A',
                'address' => $fullAddress,
                'subjects' => $class->subjects->pluck('name')->toArray(),
                'tuition' => $class->tuition,
                'classTimes' => $class->classTimes->map(fn($time) => [
                    'day' => $time->day,
                    'start' => $time->start,
                    'end' => $time->end
                ]),
            ];

            // Gửi email thông báo
            Log::info("Bắt đầu Gửi email thông báo");
            Mail::to($tutorInfo->email)->queue(new TutorAcceptedMail($tutorInfo, $classInfo, $parentInfo));
            Log::info("Kết thúc Gửi email thông báo");


            return response()->json([
                'success' => true,
                'message' => 'Xác nhận dạy lớp thành công!',
                // 'data' => $class
            ]);
        } catch (Exception $e) {
            Log::error('Unable to confirm class teaching: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác nhận nhận lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    public function getRegisterdClasses() //for parents
    {
        $this->authorize('isParent', Class1::class);

        $parent = Auth::user()->parent;

        // if (!$parent) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Phụ huynh không hợp lệ'
        //     ], 400);
        // }

        try {
            $classes = Class1::where('parent_id', $parent->id)->with([
                'tutor:id,user_id',
                'tutor.user:id,name,phone',
                'level',
                'address.ward.district',
                'subjects',
                'grade',
                'classTimes'
            ])->latest()->paginate(6);

            return response()->json($classes);
        } catch (Exception $e) {
            Log::error('Unable to enroll class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi truy xuất lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    public function completeClass(Request $request, $classId)
    {
        $class = Class1::findOrFail($classId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $this->authorize('update', $class);

        if ($class->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có lớp học đã giao mới có thể kết thúc được'
            ], 400);
        }

        try {
            $class->update([
                'status' => 2,
                'end_date' => Carbon::now()->toDateString()
            ]);

            return response()->json([
                'success' => true,
                'data' => $class,
                'message' => 'Cập nhật lớp học thành công'
            ]);
        } catch (Exception $e) {
            Log::error('Unable to complete class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết thúc lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    public function recommendClasses(Request $request)
    {
        $tutor = Auth::user()->tutor ?? null;

        if (!$tutor) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập chức năng này. Chỉ gia sư mới có thể xem gợi ý lớp học.'
            ], 403);
        }

        if (is_null($tutor->profile_status)) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin gia sư của bạn. Vui lòng cập nhật hồ sơ gia sư ngay.'
            ], 404);
        }

        // Lấy thông tin gia sư cùng các mối quan hệ
        $tutor->load(['subjects', 'grades', 'districts', 'level']);

        // Lấy danh sách môn học, khối lớp, khu vực mà gia sư có thể dạy
        $tutorSubjects = $tutor->subjects->pluck('id')->toArray();
        $tutorGrades = $tutor->grades->pluck('id')->toArray();
        $tutorDistricts = $tutor->districts->pluck('id')->toArray();

        // Lấy tất cả lớp học chưa có gia sư và các thông tin liên quan
        $query = Class1::with([
            'level',
            'subjects',
            'grade',
            'address:id,ward_id',
            'address.ward:id,name,district_id',
            'address.ward.district:id,name',
            'classTimes',
        ])
            ->whereNull('tutor_id')     // Chỉ lấy lớp chưa có gia sư
            ->where('status', 'open');  // Chỉ lấy lớp có trạng thái "open"

        // Lọc lớp học phù hợp
        $query->where(function ($q) use ($tutor, $tutorSubjects, $tutorGrades, $tutorDistricts) {
            $q->whereHas('subjects', function ($subQuery) use ($tutorSubjects) {
                $subQuery->whereIn('subjects.id', $tutorSubjects);
            })
                ->whereIn('grade_id', $tutorGrades)
                ->whereHas('address.ward.district', function ($districtQuery) use ($tutorDistricts) {
                    $districtQuery->whereIn('districts.id', $tutorDistricts);
                });

            // Kiểm tra trình độ
            if ($tutor->level_id) {
                $q->where(function ($levelQuery) use ($tutor) {
                    $levelQuery->whereNull('level_id')
                        ->orWhere('level_id', $tutor->level_id);
                });
            }

            // Kiểm tra giới tính
            $q->where(function ($genderQuery) use ($tutor) {
                $genderQuery->whereNull('gender_tutor')
                    ->orWhere('gender_tutor', $tutor->gender);
            });
        });

        // Phân trang với 6 mục trên mỗi trang
        $recommendedClasses = $query->paginate(6);

        // Trả về kết quả
        return response()->json([
            'success' => true,
            'data' => $recommendedClasses
        ]);
    }
}
