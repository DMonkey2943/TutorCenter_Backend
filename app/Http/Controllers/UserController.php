<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();
        return response()->json(
            [
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
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
    public function store(UserRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->password = Hash::make($request->password);

        $user->save();
        return response()->json(
            [
                'success' => true,
                'data' => $user,
                'message' => 'Đăng ký tài khoản thành công'
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json(
            [
                'success' => true,
                'data' => $user,
                'message' => 'User retrieved successfully'
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User not found'
                ],
                404
            );
        }

        // if ($request->name) {
        $user->name = $request->name;
        // }
        // if ($request->email) {
        $user->email = $request->email;
        // }
        // if ($request->phone) {
        $user->phone = $request->phone;
        // }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json(
            [
                'success' => true,
                'data' => $user,
                'message' => 'User updated successfully'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User not found'
                ],
                404
            );
        }

        $user->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'User deleted successfully'
            ],
            204
        );
    }
}
