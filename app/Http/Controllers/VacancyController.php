<?php

namespace App\Http\Controllers;

use App\Vacancy;
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
        return view('vacancy.view')->with(compact('vacancy'));
    }

    public function index_admin(){
        $user = auth()->user();
        $vacancies = Vacancy::paginate(10);
        return view('admin.vacancy.index')->with(compact('user','vacancies'));
    }

    public function view_admin(Vacancy $vacancy){
        $user = auth()->user();
        $vacancy = $vacancy;
        return view('admin.vacancy.view')->with(compact('user','vacancy'));
    }

    public function edit_admin(Vacancy $vacancy){
        $user = auth()->user();
        $vacancy = $vacancy;
        return view('admin.vacancy.edit')->with(compact('user','vacancy'));
    }

    public function update_admin(Request $request, Vacancy $vacancy){

        $content = request('vacancy-trixFields');

        $user = auth()->user();
        $inp = $request->all();
        $vacancy->job_title = $request->job_title;
        $vacancy->job_type = $request->job_type;
        $vacancy->job_desc = $content['job_desc'];
        $vacancy->job_req = $content['job_req'];
        $vacancy->location = $request->location;
        $vacancy->update(['vacancy-trixFields' => request('vacancy-trixFields')]);
        $vacancy->update();
        //dd($vacancy->job_desc);

        return view('admin.vacancy.view')->with(compact('vacancy','user'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
