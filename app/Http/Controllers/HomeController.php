<?php

namespace App\Http\Controllers;
use App\Vacancy;
use App\Application;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {   $vacancies = Vacancy::paginate(10);
        $applications = Application::orderBy('created_at','DESC')->paginate(10);
        $processed_apl = Application::where('status', 'Processed')->get();
        $open_apl = Application::where('status','Submitted')->get();
        $user = auth()->user();
        return view('pages.dashboard')->with(compact('vacancies','user','applications','processed_apl','open_apl'));
    }
}
