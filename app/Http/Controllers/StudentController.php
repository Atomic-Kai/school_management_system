<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->search;
            $page = $request->page;
            $total = ($page - 1) * 5;
            if ($search) {
                $students = Student::where(Student::FIRST_NAME, 'like', '%' . $search . '%')
                    ->orWhere(Student::LAST_NAME, 'like', '%' . $search . '%')
                    ->offset($total)
                    ->limit(5)
                    ->get();
                $total_pages = Student::where(Student::FIRST_NAME, 'like', '%' . $search . '%')
                    ->orWhere(Student::LAST_NAME, 'like', '%' . $search . '%')
                    ->count(Student::ID);

                $total_pages = ceil($total_pages /  5);
            } else {
                $students = Student::offset($total)->limit(5)->get();
                $total_pages = ceil(Student::count(Student::ID) / 5);
            }
            return view('student.index', compact('students', 'total_pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $courses = Course::get(['id', 'title'])->all();
        return view('student.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => ['required', 'min:2', 'max:10'],
            "last_name" => ['required', 'min:2', 'max:10'],
            "gender" => ['required', 'min:4', 'max:6'],
            "score" => ['required', 'max:3'],
            "status" => ['required'],
            "course_id" => ['required'],

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = implode(", ", $errors->all());
            return back()->with("Error", $message);
        }

        // dd($validate->fails(), $validate->errors());
            Student::create([
            Student::FIRST_NAME => $request->first_name,
            Student::LAST_NAME  => $request->last_name,
            Student::GENDER     => $request->gender,
            Student::SCORE      => $request->score,
            Student::STATUS     => $request->status,
            Student::COURSE_ID  => $request->course_id,
            Student::USER_ID    => Auth::id(),
        ]);

        return back()->with('Success', 'Student Create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $courses = Course::get(['id', 'title'])->all();
        $student = Student::find($id);
        return view('student.update', compact('student','courses'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);
        if($student) {
            $validator = Validator::make($request->all(), [
            "first_name" => ['required', 'min:2', 'max:10'],
            "last_name" => ['required', 'min:2', 'max:10'],
            "gender" => ['required', 'min:4', 'max:6'],
            "score" => ['required', 'max:3'],
            "status" => ['required'],
            "course_id" => ['required'],

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = implode(", ", $errors->all());
            return back()->with("Error", $message);
        }

        // dd($validate->fails(), $validate->errors());
            Student::where(Student::ID, $id)->update([
            Student::FIRST_NAME => $request->first_name,
            Student::LAST_NAME  => $request->last_name,
            Student::GENDER     => $request->gender,
            Student::SCORE      => $request->score,
            Student::STATUS     => $request->status,
            Student::COURSE_ID  => $request->course_id,
            Student::USER_ID    => Auth::id(),
            ]);
            return redirect()->route('index.student')->with('Success', 'Student Updated');
        } else {
            return redirect()->route('index.student')->with('Error', 'Student not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $student = Student::find($request->remove_id);
        if ($student) {
            Student::where(Student::ID, $request->remove_id)->delete();
            return redirect()->back()->with('Success', 'Student Deleted');
        } else {
            return redirect()->back()->with('Error', 'Student not found');
        }
    }
}
