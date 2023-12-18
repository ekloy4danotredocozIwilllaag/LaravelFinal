<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Job;

use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index() 
    {
        $jobs = Job::orderBy('id')->get();

        return view('job.index', ['jobs' => $jobs]);
    }

    public function create() {

        $users = User::list();
        return view('job.create', ['users' => $users]);
    }

    public function store(Request $request) {

        $request->validate([
            'user_id' => 'required|numeric',
            'status' => 'required',
            'occupation' => 'required'
        ]);

        Job::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'occupation' => $request->occupation
        ]);

        return redirect('/jobs')->with('message', 'A new user has been added');
    }
    
    public function edit(Job $jobModel, $id)
    {
        $job = Job::findOrFail($id);
        $users = User::all();
        return view('job.edit', compact('job', 'users'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'status' => 'required',
            'occupation' => 'required'
        ]);
    
        $job = Job::findOrFail($id);
        $job->update($request->all());
    
        return redirect('/jobs')->with('message', "$job->user_id has been updated.");
    }
    

    public function destroy($id)
    {
        try {
            $job = Job::findOrFail($id);
            $job->delete();

            return redirect('/jobs')->with('message', 'Job deleted successfully');
        } catch (\Exception $e) {
            return redirect('/jobs')->with('error', 'Error deleting job: ' . $e->getMessage());
        }
    }
}

