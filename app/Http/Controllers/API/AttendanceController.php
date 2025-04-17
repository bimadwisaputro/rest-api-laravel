<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{

    public function attendanceIn(Request $request)
    {
        $lat_from_employee = $request->lat_from_employee;
        $long_from_employee = $request->long_from_employee;
        $lat_from_office = $request->lat_from_office;
        $long_from_office = $request->long_from_office;
        $radius = $this->getDistanceBetweenPoints($lat_from_employee, $long_from_employee, $lat_from_office, $long_from_office);
        return $radius;
    }

    protected function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet  = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }

    public function index()
    {
        try {
            $Attendances = Attendances::orderBy("id", "desc")->with('employee', 'office')->get();
            return response()->json(['success' => true, 'data' => $Attendances], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data Attendances: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'employee_id' => 'required',
            'office_id' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 422);
        }
        try {
            $Attendances = Attendances::create([
                'employee_id' => $request->employee_id,
                'office_id' => $request->office_id,
                'lat_from_employee' => $request->lat_from_employee,
                'long_from_employee' => $request->long_from_employee,
                'lat_from_office' => $request->lat_from_office,
                'long_from_office' => $request->long_from_office,
                'attendance_in' => $request->attendance_in,
                'attendance_out' => $request->attendance_out,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Attendance added success.', 'data' => $Attendances], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to add data Attendances: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function show(string $id)
    {
        try {
            // $Attendances = Attendances::orderBy("id", "desc")->get();
            $Attendances = Attendances::with('employee', 'office')->findorFail($id);
            return response()->json(['success' => true, 'data' => $Attendances], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data Attendances: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'employee_id' => $request->employee_id,
            'office_id' => $request->office_id,
            'lat_from_employee' => $request->lat_from_employee,
            'long_from_employee' => $request->long_from_employee,
            'lat_from_office' => $request->lat_from_office,
            'long_from_office' => $request->long_from_office,
            'attendance_in' => $request->attendance_in,
            'attendance_out' => $request->attendance_out,
            'status' => $request->status,
            'description' => $request->description,
        ];

        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['success' => false, 'message' => $validate->errors()], 422);
        }

        try {
            // $Attendances = Attendances::orderBy("id", "desc")->get();
            $Attendances = Attendances::find($id);
            $Attendances->update($data);
            return response()->json(['success' => true, 'message' => 'Attendance updated success.', 'data' => $Attendances], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data Attendances: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $Attendances = Attendances::find($id);
            $Attendances->delete();
            return response()->json(['success' => true, 'message' => 'Attendance Deleted success.', 'data' => $Attendances], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data Attendances: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function deletedata(string $id)
    {
        try {
            $Attendances = Attendances::find($id)->delete();
            return response()->json(['success' => true, 'message' => 'Attendance Deleted success.', 'data' => $Attendances], 200);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data Attendances: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
