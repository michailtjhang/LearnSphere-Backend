<?php

namespace App\Http\Controllers\front;

use App\Models\Requirement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RequirementController extends Controller
{
    // This Method will return all requirements of a course
    public function index(Request $request)
    {
        $requirements = Requirement::where('course_id', $request->course_id)->orderBy('sort_order')->get();

        return response()->json([
            'status' => 200,
            'data' => $requirements
        ], 200);
    }

    // This method will store/save a requirement
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'requirement' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $requirement = new Requirement();
        $requirement->course_id = $request->course_id;
        $requirement->text = $request->requirement;
        $requirement->sort_order = 1000;
        $requirement->save();

        return response()->json([
            'status' => 201,
            'message' => 'Requirement created successfully'
        ], 201);
    }

    // This method will update a outcome
    public function update(Request $request, $id)
    {
        $requirement = Requirement::find($id);

        if (!$requirement) {
            return response()->json([
                'status' => 404,
                'message' => 'Requirement not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'requirement' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $requirement->text = $request->requirement;
        $requirement->save();

        return response()->json([
            'status' => 200,
            'message' => 'Requirement updated successfully'
        ], 200);
    }

    // This method will delete a requirement
    public function destroy($id)
    {
        $requirement = Requirement::find($id);

        if (!$requirement) {
            return response()->json([
                'status' => 404,
                'message' => 'Requirement not found'
            ], 404);
        }

        $requirement->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Requirement deleted successfully'
        ], 200);
    }

    public function sortRequirements(Request $request)
    {
        if (!empty($request->requirements)) {
            foreach ($request->requirements as $key => $requirementId) {
                $requirement = Requirement::where('id', $requirementId)->update(['sort_order' => $key]);
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order saved successfully'
        ], 200);
    }
}
