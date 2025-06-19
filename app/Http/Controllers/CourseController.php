<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constants\PermissionConstant;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd(Auth::user()->getRoleNames()->toArray()); // get role as array
        if (!Auth::user()->can(PermissionConstant::VIEW_COURSE)) {
            return back()->with('Error', 'Permission Denied');
        }
        $search = $request->search;
        $page = $request->page;
        $total = ($page - 1) * 5;
        if ($search) {

            $courses = Course::orderBy('id', 'desc')
                ->where(Course::TITLE, 'like', '%' . $search . '%');

            $total_pages = Course::orderBy('id', 'desc')
                ->where(Course::TITLE, 'like', '%' . $search . '%');

            if (!in_array("admin", Auth::user()->getRoleNames()->toArray())) {
                $courses = $courses->where('user_id', Auth::user()->id);
                $total_pages = $total_pages->where('user_id', Auth::user()->id);
            }

            $courses = $courses->offset($total)
                ->limit(5)
                ->get();

            $total_pages = $total_pages->count(Course::ID);

            $total_pages = ceil($total_pages /  5);
        } else {
            $total_pages = ceil(Course::count(Course::ID) / 5);
            $courses = Course::orderBy('id', 'desc');

            if (!in_array("admin", Auth::user()->getRoleNames()->toArray())) {
                $courses = $courses->where('user_id', Auth::user()->id);
                $total_pages = ceil(Course::where('user_id', Auth::user()->id)->count(Course::ID) / 5);
            }

            $courses = $courses->offset($total)
                ->limit(5)
                ->get();;
        }

        return view('course.index', compact('courses', 'total_pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->can(PermissionConstant::CREATE_COURSE)) {
            return back()->with('Error', 'Permission Denied');
        }
        $teachers = User::get(['id', 'first_name', 'last_name', 'gender'])->all();
        return view('course.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'price' => 'required',
            'start_date' => 'required',
            'teacher_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages();
            $messsage = implode(", ", $errors->all());
            return back()->with("Error", $messsage);
        }

        Course::create([
            Course::TITLE => $request->title,
            Course::PRICE => $request->price,
            Course::START_DATE => $request->start_date,
            Course::END_DATE => $request->end_date,
            Course::USER_ID => $request->teacher_id,
            Course::DESCRIPTION => $request->description
        ]);
        return back()->with('Success', 'course Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!Auth::user()->can(PermissionConstant::EDIT_COURSE)) {
            return back()->with('Error', 'Permission Denied');
        }
        $teachers = User::get(['id', 'first_name', 'last_name', 'gender'])->all();
        $course = Course::find($id);
        return view('course.update', compact('teachers', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can(PermissionConstant::EDIT_COURSE)) {
            return back()->with('Error', 'Permission Denied');
        }
        $course = Course::find($id);
        if ($course) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:255',
                'price' => 'required',
                'start_date' => 'required',
                'teacher_id' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->messages();
                $messsage = implode(", ", $errors->all());
                return back()->with("Error", $messsage);
            }

            Course::where(Course::ID, $id)->update([
                Course::TITLE => $request->title,
                Course::PRICE => $request->price,
                Course::START_DATE => $request->start_date,
                Course::END_DATE => $request->end_date,
                Course::USER_ID => $request->teacher_id,
                Course::DESCRIPTION => $request->description
            ]);
            return redirect()->route('index.course')->with('Success', 'course Updated');
        } else {
            return redirect()->route('index.course')->with('Error', 'course not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $course = Course::find($request->remove_id);
        if ($course) {
            Course::where(Course::ID, $request->remove_id)->delete();
            return redirect()->back()->with('Success', 'course Deleted');
        } else {
            return redirect()->back()->with('Error', 'course not found');
        }
    }
}
