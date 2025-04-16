<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employees::orderBy("id", "desc")->with('user')->get();
            return response()->json(['success' => true, 'data' => $employees], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data employees: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 422);
        }
        try {
            $employees = Employees::create([
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'nip' => $request->nip,
                'address' => $request->address,
                'is_active' => $request->is_active,
                'gender' => $request->gender,
            ]);

            return response()->json(['success' => true, 'message' => 'Employee added success.', 'data' => $employees], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to add data employees: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function show(string $id)
    {
        try {
            // $employees = Employees::orderBy("id", "desc")->get();
            $employees = Employees::with('user')->findorFail($id);
            return response()->json(['success' => true, 'data' => $employees], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data employees: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'user_id' => $request->user_id,
            'phone' => $request->phone,
            'nip' => $request->nip,
            'address' => $request->address,
            'is_active' => $request->is_active,
            'gender' => $request->gender,
        ];

        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 422);
        }

        try {
            // $employees = Employees::orderBy("id", "desc")->get();
            $employees = Employees::find($id);
            $employees->update($data);
            return response()->json(['success' => true, 'message' => 'Employee updated success.', 'data' => $employees], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data employees: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $employees = Employees::find($id);
            $employees->delete();
            return response()->json(['success' => true, 'message' => 'Employee Deleted success.', 'data' => $employees], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data employees: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
