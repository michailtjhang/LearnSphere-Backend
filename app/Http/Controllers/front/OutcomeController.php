<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Outcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutcomeController extends Controller
{
    // This Method will return all outComes of a course
    public function index(Request $request)
    {
        $outcomes = Outcome::where('course_id', $request->course_id)->get();

        return response()->json([
            'status' => 200,
            'data' => $outcomes
        ], 200);
    }

    // This method will store/save a outcome
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'outcome' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $outcome = new Outcome();
        $outcome->course_id = $request->course_id;
        $outcome->text = $request->outcome;
        $outcome->sort_order = 1000;
        $outcome->save();

        return response()->json([
            'status' => 201,
            'message' => 'Outcome created successfully'
        ], 201);
    }

    // This method will update a outcome
    public function update(Request $request, $id)
    {
        $outcome = Outcome::find($id);

        if (!$outcome) {
            return response()->json([
                'status' => 404,
                'message' => 'Outcome not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'outcome' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $outcome->text = $request->outcome;
        $outcome->save();

        return response()->json([
            'status' => 200,
            'message' => 'Outcome updated successfully'
        ], 200);
    }

    // This method will delete a outcome
    public function destroy($id)
    {
        $outcome = Outcome::find($id);

        if (!$outcome) {
            return response()->json([
                'status' => 404,
                'message' => 'Outcome not found'
            ], 404);
        }

        $outcome->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Outcome deleted successfully'
        ], 200);
    }
}