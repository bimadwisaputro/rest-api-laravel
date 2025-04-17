<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    public function index()
    {
        try {
            $offices = offices::orderBy("id", "desc")->get();
            return response()->json(['success' => true, 'data' => $offices], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data offices: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'office_name' => 'required',
            'office_lat' => 'required',
            'office_long' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 422);
        }
        try {
            $offices = offices::create([
                'office_name' => $request->office_name,
                'office_phone' => $request->office_phone,
                'office_address' => $request->office_address,
                'office_lat' => $request->office_lat,
                'office_long' => $request->office_long,
                'is_active' => $request->is_active,
            ]);

            return response()->json(['success' => true, 'message' => 'Employee added success.', 'data' => $offices], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to add data offices: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function show(string $id)
    {
        try {
            // $offices = offices::orderBy("id", "desc")->get();
            $offices = offices::findorFail($id);
            return response()->json(['success' => true, 'data' => $offices], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data offices: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'office_name' => $request->office_name,
            'office_phone' => $request->office_phone,
            'office_address' => $request->office_address,
            'office_lat' => $request->office_lat,
            'office_long' => $request->office_long,
            'is_active' => $request->is_active,
        ];

        $validate = Validator::make($request->all(), [
            'office_name' => 'required',
            'office_lat' => 'required',
            'office_long' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 422);
        }

        try {
            // $offices = offices::orderBy("id", "desc")->get();
            $offices = offices::find($id);
            $offices->update($data);
            return response()->json(['success' => true, 'message' => 'Employee updated success.', 'data' => $offices], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data offices: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $offices = offices::find($id);
            $offices->delete();
            return response()->json(['success' => true, 'message' => 'Employee Deleted success.', 'data' => $offices], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data offices: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function deletedata(string $id)
    {
        try {
            $offices = offices::find($id)->delete();
            return response()->json(['success' => true, 'message' => 'Employee Deleted success.', 'data' => $offices], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data offices: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
