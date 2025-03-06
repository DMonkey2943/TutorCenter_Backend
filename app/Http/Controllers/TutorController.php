<?php

namespace App\Http\Controllers;

use App\Http\Requests\TutorRequest;
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
    public function index()
    {
        $tutors = Tutor::with([
            'user',
            'level',
            'subjects',
            'grades'
        ])->latest('id')->get();
        return response()->json(
            [
                'success' => true,
                'data' => $tutors,
                'message' => 'Tutors retrieved successfully'
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
        $tutor = Tutor::with([
            'user',
            'level',
            'tuition',
            'districts',
            'subjects',
            'grades'
        ])->find($id);

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
     * Show the form for editing the specified resource.
     */
    public function edit(TutorRequest $tutor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TutorRequest $request, $id)
    {
        // dd($request->all(), $request->file('avatar'));
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
}
