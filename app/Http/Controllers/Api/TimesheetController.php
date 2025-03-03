<?php

namespace App\Http\Controllers\Api;

use App\Models\Timesheet;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeSheetRequest;
use App\Http\Resources\TimeSheetResource;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Time;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $timesheets = TimeSheetResource::collection($user->timesheets);
        return response()->json($timesheets);
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(TimeSheetRequest $request)
    {
        $user = $request->user();
        $user->timesheets()->create($request->validated());
        return response()->json(['message' => 'Timesheet created successfully']);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Timesheet $timesheet)
    {
        $timesheet = new TimeSheetResource($timesheet);
        return response()->json($timesheet);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TimeSheetRequest $request, Timesheet $timesheet)
    {
        $timesheet->update($request->all());
        return response()->json(['message' => 'Timesheet updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();
        return response()->json(['message' => 'Timesheet deleted successfully']);
    }
}
