<?php

namespace App\Http\Controllers;

use App\Models\Parent1;
use Illuminate\Http\Request;

class Parent1Controller extends Controller
{
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
        $parents = Parent1::with('user')->latest('id')->get();
        return response()->json(
            [
                'success' => true,
                'data' => $parents,
                'message' => 'Parents retrieved successfully'
            ]
        );
    }

    public function getAll()
    {
        $parents = Parent1::with('user')->get()->map(function ($parent) {
            return [
                'value' => $parent->id, // Đổi id thành value
                'label' => "{$parent->id}: " . $parent->user->name, // Đổi name thành label (kiểm tra null)
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $parents,
            'message' => 'Parents retrieved successfully'
        ]);
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
        $parent = new Parent1;
        $parent->fill($request->all());

        $parent->save();
        return response()->json(
            [
                'success' => true,
                'data' => $parent,
                'message' => 'Tạo phụ huynh thành công'
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parent = Parent1::with('user')->find($id);

        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Parent not found'
            ], 404);
        }

        return response()->json(
            [
                'success' => true,
                'data' => $parent,
                'message' => 'Parent retrieved successfully'
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parent1 $parent1)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $parent = Parent1::find($id);

        if (!$parent) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Parent not found'
                ],
                404
            );
        }

        if ($request->gender) {
            $parent->gender = $request->gender;
        }

        $parent->save();
        return response()->json(
            [
                'success' => true,
                'data' => $parent,
                'message' => 'Parent updated successfully'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $parent = Parent1::find($id);

        if (!$parent) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Parent not found'
                ],
                404
            );
        }

        $parent->delete();
        $this->userController->destroy($parent->user_id);

        return response()->json(
            [
                'success' => true,
                'message' => 'Parent deleted successfully'
            ],
            204
        );
    }
}
