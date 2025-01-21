<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Tutor;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $userController;
    protected $parent1Controller;
    protected $tutorController;

    public function __construct(UserController $userController, Parent1Controller $parent1Controller, TutorController $tutorController)
    {
        $this->userController = $userController;
        $this->parent1Controller = $parent1Controller;
        $this->tutorController = $tutorController;
    }

    /**
     * Register a new user.
     */
    public function register(UserRequest $request)
    {
        try {
            $response = $this->userController->store($request);
            $user = $response->getData()->data;

            if ($user->role === 'parent') {
                $this->parent1Controller->store(new Request(
                    [
                        'user_id' => $user->id
                    ]
                ));
            }
            if ($user->role === 'tutor') {
                $this->tutorController->createAccount(new Request(
                    [
                        'user_id' => $user->id
                    ]
                ));
            }

            return $response;
        } catch (Exception $e) {
            Log::error('Unable to Register user: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi đăng ký tài khoản: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request)
    {
        try {
            $status = Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không chính xác'
                ], 401);
            };

            $user = Auth::user();
            // Create API token
            $token = $request->user()->createToken('auth')->plainTextToken;
            return response()->json([
                'success' => true,
                'token' => $token,
                'data' => $user,
                'message' => 'Đăng nhập thành công'
            ]);
        } catch (Exception $e) {
            Log::error('Unable to Login user: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi đăng nhập: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function profile()
    {
        try {
            $user = Auth::user();

            if ($user) {
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'message' => 'Lấy thông tin người dùng thành công'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin người dùng'
            ], 401);
        } catch (Exception $e) {
            Log::error('Unable to Fetch User Profile: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin người dùng: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $user->currentAccessToken()->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng xuất thành công'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Lỗi đăng xuất'
            ], 401);
        } catch (Exception $e) {
            Log::error('Unable to Logout User: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi đăng xuất: ' . $e->getMessage()
            ], 400);
        }
    }
}
