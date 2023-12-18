<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index() 
    {
        $s = Student::orderBy('id')->get();

        return view('student.index', ['students' => $s]);
    }

    public function create() {

        $users = User::list();
        return view('student.create', ['users' => $users]);
    }

    public function store(Request $request) {

        $request->validate([
            'user_id' => 'required|numeric',
            'course' => 'required',
            'year' => 'required'
        ]);

        Student::create([
            'user_id' => $request->user_id,
            'course' => $request->course,
            'year' => $request->year
        ]);

        return redirect('/students')->with('message', 'A new Student has been added');
    }

    public function edit(Student $student, $id)
    {
        // $users = user::with('user')->find($users->id);
        $student = Student::findOrFail($id);

        $users = User::all();
        return view('student.edit', compact('student', 'users'));
    }

    public function update( Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'course' => 'required',
            'year' => 'required'
        ]);

        $student = Student::findOrFail($id);
        $student -> update($request->all());
        return redirect('/students')->with('message', "$student->user_id has been updated.");
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return redirect('/students')->with('message', 'students deleted successfully');
        } catch (\Exception $e) {
            return redirect('/student')->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }

}
