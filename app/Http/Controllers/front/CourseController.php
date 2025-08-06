<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // This method will return all courses for a specific user
    public function index()
    {

    }

    // This method will store/save a course in database as a draf
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:5|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ], 400);
        }

        // This will store course in db
        $course = new Course();
        $course->title = $request->title;
        $course->status = 0;
        $course->user_id = $request->user()->id;
        $course->save();

        return response()->json([
            'status' => 200,
            'data' => $course,
            'message' => 'Course has been created successfully',
        ], 200);
    }

    public function show($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 404,
                'message' => 'Course not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $course,
        ], 200);
    }

    // This method will return categories/levels/languages
    public function metaData() {
        $categories = \App\Models\Category::get();
        $levels = \App\Models\Level::get();
        $languages = \App\Models\Language::get();

        return response()->json([
            'status' => 200,
            'data' => [
                'categories' => $categories,
                'levels' => $levels,
                'languages' => $languages,
            ],
        ], 200);
    }
}
