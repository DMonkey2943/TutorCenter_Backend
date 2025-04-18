<?php

namespace App\Http\Controllers;

use App\Http\Requests\TutorRequest;
use App\Models\Parent1;
use App\Models\Tutor;
use App\Models\TutorDistrict;
use App\Models\TutorGrade;
use App\Models\TutorSubject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Traits\HandleImageTrait;

class TutorController extends Controller
{
    use HandleImageTrait;

    protected $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tutor::class);

        $query = Tutor::with([
            'user',
            'level',
            'subjects',
            'grades'
        ]);

        // Kiểm tra nếu có tham số status trong request
        if ($request->has('profile_status')) {
            $profile_status = $request->query('profile_status');
            $query->where('profile_status', $profile_status);
        }

        $tutors = $query->latest('id')->get();
        return response()->json(
            [
                'success' => true,
                'data' => $tutors,
                'message' => 'Tutors retrieved successfully'
            ]
        );
    }

    public function createAccount(Request $request)
    {
        $tutor = new Tutor();
        $tutor->fill($request->all());

        $tutor->save();
        return response()->json(
            [
                'success' => true,
                'data' => $tutor,
                'message' => 'Tạo gia sư thành công'
            ],
            201
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TutorRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['profile_status'] = 0;

        // Xử lý ảnh Avatar
        $fileAvt = $request->file('avatar');
        $data['avatar'] = $this->uploadImage($fileAvt, 'images/avatars');

        // Xử lý ảnh Degree
        $fileDeg = $request->file('degree');
        $data['degree'] = $this->uploadImage($fileDeg, 'images/degrees');


        try {
            $profileTutor = new Tutor;
            $profileTutor->fill($data);
            $profileTutor->save();

            $id = $profileTutor->id;

            foreach ($data['districts'] as $district) {
                TutorDistrict::create([
                    'tutor_id' => $id,
                    'district_id' => $district,
                ]);
            };
            foreach ($data['subjects'] as $subject) {
                TutorSubject::create([
                    'tutor_id' => $id,
                    'subject_id' => $subject,
                ]);
            };
            foreach ($data['grades'] as $grade) {
                TutorGrade::create([
                    'tutor_id' => $id,
                    'grade_id' => $grade,
                ]);
            };

            return response()->json(
                [
                    'success' => true,
                    'data' => $profileTutor,
                    'message' => 'Tạo hồ sơ gia sư thành công'
                ],
                201
            );
        } catch (Exception $e) {
            Log::error('Unable to create tutor\'s profile: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tạo hồ sơ gia sư: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $role = $user->role;
        $tutor = null;
        if ($role == 'parent') {
            $tutor = Tutor::with([
                'user:id,name',
                'level',
                'tuition',
                'subjects',
                'grades'
            ])->findOrFail($id)->makeHidden([
                'address',
                'degree',
                'profile_reason',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        } else {
            $tutor = Tutor::with([
                'user',
                'level',
                'tuition',
                'districts',
                'subjects',
                'grades'
            ])->findOrFail($id);
        }

        if (!$tutor) {
            return response()->json([
                'success' => false,
                'message' => 'Tutor\'s profile not found'
            ], 404);
        }

        return response()->json(
            [
                'success' => true,
                'data' => $tutor,
                'message' => 'Tutor\'s profile retrieved successfully'
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TutorRequest $request, $id)
    {
        // dd($request->all(), $request->file('avatar'));
        $profileTutor = Tutor::find($id);

        $this->authorize('update', $profileTutor);

        if (!$profileTutor) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Tutor\'s profile not found'
                ],
                404
            );
        }

        $data = $request->all();
        // Nếu có ảnh đại diện được upload
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($profileTutor->avatar) {
                $this->deleteImage($profileTutor->avatar);
            }
            // Lưu ảnh Avatar mới
            $fileAvt = $request->file('avatar');
            $profileTutor->avatar = $this->uploadImage($fileAvt, 'images/avatars');
            $profile_status = 0;
        }
        // Nếu có ảnh bằng cấp/thẻ SV được upload
        if ($request->hasFile('degree')) {
            // Xóa ảnh cũ nếu có
            if ($profileTutor->degree) {
                $this->deleteImage($profileTutor->degree);
            }
            // Lưu ảnh Degree mới
            $fileAvt = $request->file('degree');
            $profileTutor->degree = $this->uploadImage($fileAvt, 'images/degrees');
            $profile_status = 0;
        }

        if ($request->major) {
            $profileTutor->major = $data['major'];
            $profile_status = 0;
        }
        if ($request->school) {
            $profileTutor->school = $data['school'];
            $profile_status = 0;
        }
        if ($request->level_id) {
            $profileTutor->level_id = $data['level_id'];
            $profile_status = 0;
        }
        if ($request->gender) {
            $profileTutor->gender = $data['gender'];
        }
        if ($request->address) {
            $profileTutor->address = $data['address'];
        }
        if ($request->birthday) {
            $profileTutor->birthday = $data['birthday'];
        }
        // if ($request->experiences) {
        $profileTutor->experiences = $data['experiences'];
        // }
        if ($request->tuition_id) {
            $profileTutor->tuition_id = $data['tuition_id'];
        }
        $profileTutor->profile_status = $profile_status;

        try {
            $profileTutor->save();

            if ($request->districts) {
                $profileTutor->districts()->sync($data['districts']);
            }
            if ($request->subjects) {
                $profileTutor->subjects()->sync($data['subjects']);
            }
            if ($request->grades) {
                $profileTutor->grades()->sync($data['grades']);
            }

            return response()->json(
                [
                    'success' => true,
                    'data' => $profileTutor,
                    'message' => 'Tutor\'s profile updated successfully'
                ]
            );
        } catch (Exception $e) {
            Log::error('Unable to update tutor\'s profile: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi cập nhật hồ sơ gia sư: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tutor = Tutor::find($id);

        $this->authorize('delete', $tutor);

        if (!$tutor) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Tutor not found'
                ],
                404
            );
        }

        $tutor->delete();
        $this->userController->destroy($tutor->user_id);

        return response()->json(
            [
                'success' => true,
                'message' => 'Tutor deleted successfully'
            ],
            204
        );
    }

    public function getTutorByUserId($userId)
    {
        $tutor = Tutor::where('user_id', $userId)->first();

        $this->authorize('view', $tutor);

        if (Auth::user()->role == 'parent') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập tài nguyên này.'
                ],
                403
            );
        }

        if (!$tutor) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Tutor not found'
                ],
                404
            );
        }

        return response()->json(
            [
                'success' => true,
                'data' => $tutor,
                'message' => 'Tutor retrieved successfully'
            ]
        );
    }

    public function getAvailableTutors(Request $request)
    {
        $this->authorize('isParent', Tutor::class);

        $validated = $request->validate([
            'district_id' => 'nullable|integer',
            'subjects' => 'nullable|array',
            'subjects.*' => 'integer',
            'grade_id' => 'nullable|integer',
            'level_id' => 'nullable|integer',
            'gender' => 'nullable|in:M,F',
        ]);

        try {
            $query = Tutor::with([
                'user:id,name',
                'level',
                'subjects',
                'grades',
                'tuition',
                'districts',
            ])->where('profile_status', 1);

            // Filter by subject - get tutors who can teach ALL requested subjects
            if (!empty($validated['subjects'])) {
                $subjectIds = $validated['subjects'];

                // For each subject, get tutors who can teach it
                foreach ($subjectIds as $subjectId) {
                    $query->whereHas('subjects', function ($q) use ($subjectId) {
                        $q->where('subject_id', $subjectId);
                    });
                }
            }

            // Filter by grade
            if (!empty($validated['grade_id'])) {
                $query->whereHas('grades', function ($q) use ($validated) {
                    $q->where('grade_id', $validated['grade_id']);
                });
            }

            // Filter by district if provided
            if (!empty($validated['district_id'])) {
                $query->whereHas('districts', function ($q) use ($validated) {
                    $q->where('district_id', $validated['district_id']);
                });
            }

            // Filter by tutor level if provided
            if (!empty($validated['level_id'])) {
                $query->where('level_id', $validated['level_id']);
            }

            // Filter by gender if provided
            if (!empty($validated['gender'])) {
                $query->where('gender', $validated['gender']);
            }

            // Get the filtered tutors
            $tutors = $query->latest('id')->get()->makeHidden([
                'address',
                'degree',
                'profile_reason',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

            return response()->json(
                [
                    'success' => true,
                    'data' => $tutors,
                    'message' => 'Available tutors retrieved successfully'
                ]
            );
        } catch (Exception $e) {
            Log::error('Unable to filter tutors for class: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lọc gia sư phù hợp cho lớp học: ' . $e->getMessage()
            ], 400);
        }
    }

    public function approveProfile(Request $request, $id)
    {
        $this->authorize('isAdmin', Tutor::class);

        $profileTutor = Tutor::find($id);

        if (!$profileTutor) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Tutor\'s profile not found'
                ],
                404
            );
        }

        try {
            $profileTutor->profile_status = $request->profile_status;

            if ($request->profile_reason) {
                $profileTutor->profile_reason = $request->profile_reason;
            }

            $profileTutor->save();
            return response()->json(
                [
                    'success' => true,
                    'data' => $profileTutor,
                    'message' => 'Approving tutor\'s profile successfully'
                ]
            );
        } catch (Exception $e) {
            Log::error('Unable to approve tutor\'s profile: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xét duyệt hồ sơ gia sư: ' . $e->getMessage()
            ], 400);
        }
    }

    public function getAverageRating($id)
    {
        try {
            $tutor = Tutor::findOrFail($id);

            if (!$tutor) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Tutor not found'
                    ],
                    404
                );
            }

            $averageRating = $tutor->average_rating;
            $totalRatings = $tutor->rates()->count();

            return response()->json(
                [
                    'success' => true,
                    'data' => [
                        'average_rating' => round($averageRating, 1), // Làm tròn đến 1 chữ số thập phân
                        'total_ratings' => $totalRatings
                    ],
                    'message' => 'Get tutor\'s average rating successfully'
                ]
            );
        } catch (Exception $e) {
            Log::error('Unable to Get tutor\'s average rating: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy đánh giá trung bình của gia sư: ' . $e->getMessage()
            ], 400);
        }
    }
}
