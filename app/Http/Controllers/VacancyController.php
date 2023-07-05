<?php

namespace App\Http\Controllers;

use App\Vacancy;
use App\Application;
use App\VacancyLog;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function view(Vacancy $vacancy){
        if($vacancy->is_frontpage == "Yes"){
            return view('vacancy.view')->with(compact('vacancy'));
        }
        return redirect()->route('homepage')->with('error', "We don't think you should see that.");
    }

    public function index_admin(){
        $user = auth()->user();
        $vacancies = Vacancy::where('is_active', 'Yes')->orderBy('created_at','DESC')->paginate(10);
        $inactive_vacancies = Vacancy::where('is_active', 'No')->orderBy('created_at','DESC')->paginate(10);
        return view('admin.vacancy.index')->with(compact('user','vacancies','inactive_vacancies'));
    }

    public function view_admin(Vacancy $vacancy){
        $user = auth()->user();
        $vacancy = $vacancy;
        $vac_apl = Application::where('vacancy_id', $vacancy->id)->orderBy('id', 'DESC')->paginate(10);
        $vac_log_created = VacancyLog::where('vacancy_id', $vacancy->id)->where('action','created')->first();
        $vac_log_edited = VacancyLog::where('vacancy_id', $vacancy->id)->where('action','edited')->latest()->first();

        return view('admin.vacancy.view')->with(compact('user','vacancy', 'vac_apl','vac_log_created','vac_log_edited'));
    }

    public function edit_admin(Vacancy $vacancy){
        $user = auth()->user();
        $vacancy = $vacancy;
        return view('admin.vacancy.edit')->with(compact('user','vacancy'));
    }

    public function update_admin(Request $request, Vacancy $vacancy){

        $content = request('vacancy-trixFields');
        //dd($request->all());

        $user = auth()->user();
        $vacancy->job_title = $request->job_title;
        $vacancy->job_type = $request->job_type;
        $vacancy->job_desc = $request->job_desc;
        $vacancy->job_req = $request->job_req;
        $vacancy->location = $request->location;
        //$vacancy->update(['vacancy-trixFields' => request('vacancy-trixFields')]);
        $vacancy->update();
        //dd($vacancy->job_desc);

        $vac_log = new VacancyLog;
        $vac_log->user_id = $user->id;
        $vac_log->vacancy_id = $vacancy->id;
        $vac_log->action = "edited";
        $vac_log->save();

        // return view('admin.vacancy.view')->with(compact('vacancy','user'));
        return redirect()->route('admin-view-vacancy', ['vacancy' => $vacancy])->with(compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        return view('admin.vacancy.create')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $vac = new Vacancy;
        $vac->job_title = $request->job_title;
        $vac->job_type = $request->job_type;
        $vac->location = $request->location;

        $richtext = request('vacancy-trixFields');
        $vac->job_desc = $richtext['job_desc'];
        $vac->job_req = $richtext['job_req'];
        $vac->save();

        $vac_log = new VacancyLog;
        $vac_log->user_id = $user->id;
        $vac_log->vacancy_id = $vac->id;
        $vac_log->action = "created";
        $vac_log->save();

        return redirect()->route('admin-view-vacancies')->with('message', "Vacancy created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function show(Vacancy $vacancy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function edit(Vacancy $vacancy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacancy $vacancy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->is_active = "No";
        $vacancy->save();
        return redirect()->route('admin-view-vacancies')->with('message', "Vacancy deleted successfully");
    }

    public function toggle_frontpage(Vacancy $vacancy){
        if($vacancy->is_frontpage == "Yes"){
            $vacancy->is_frontpage = "No";
            $vacancy->save();
            return redirect()->route('admin-view-vacancies')->with('message', "Vacancy hidden on frontpage successfully");
        }
        $vacancy->is_frontpage = "Yes";
            $vacancy->save();
            return redirect()->route('admin-view-vacancies')->with('message', "Vacancy published on frontpage successfully");
    }
}
