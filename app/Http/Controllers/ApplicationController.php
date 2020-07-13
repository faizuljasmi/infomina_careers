<?php

namespace App\Http\Controllers;

use App\Application;
use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\ApplicationMeta;
use Mail;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_admin()
    {
        $user = auth()->user();
        $applications = Application::paginate(10);
        return view('admin.application.index')->with(compact('user','applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Vacancy $vacancy)
    {
        return view('vacancy.apply')->with(compact('vacancy'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Vacancy $vacancy)
    {
        $request->flash();
        $validator = Validator::make(
            $request->all(),
            ['resume_applicant' => 'mimes:pdf,docx,doc|required|max:20000',]
        );

         // If validation fails, seek for specific error
         if ($validator->fails()) {
            $error = 'Error: <br>';
            //If RESUME not uploaded
            if($request->file('resume_applicant') === null){
                $error =  $error.'Please upload your resume.<br>';
            }

            //If GAMBAR PEMOHON uploaded in wrong format
            if($request->hasFile('resume_applicant') == true){
                if(strpos($request->file('resume_applicant')->getMimeType(), 'pdf') !==  0 || strpos($request->file('resume_applicant')->getMimeType(), 'docx') !==  0 || strpos($request->file('resume_applicant')->getMimeType(), 'docx') !==  0 ){
                    $error = $error.'Your uploaded file is not in the correct format.';
                }
            }
            return redirect()->route('create-application', ['vacancy' => $vacancy])->with('error', $error);
        }

        $inputs = $request->all();
        $app = new Application;
        $app->vacancy_id = $vacancy->id;

        //Get the latest application
        $latest = Application::latest()->first();
        //Mod the app id by 100, to get number range from 0-99
        $app_id = 0;
        if($latest == null){
           $app_id = 1;
        }
        else{
            $app_id = ($latest->id % 9) + 1;
            $app_id++;
        }
        //Increment the id
        //Conv to string
        $app_id = (string) $app_id;
        //Initialize no permohonan
        $no_permohonan = "";
        //Get tarikh, remove -
        $dt = str_replace("-","",Carbon::today()->toDateString());
        //Get timestamp
        $timestmp = Carbon::now()->format('His');
        //Get vacancy id
        $vac_id = $vacancy->id;
        //Combine all
        $app->apl_no = $vac_id.$dt.$timestmp.$app_id;

        $app->save();

        foreach ($inputs as $key => $val) {
            if (strpos($key, '_token') === 0 || strpos($key, 'attachment') === 0) {
                continue;
            }
            $appMeta = new ApplicationMeta;
            $appMeta->application_id = $app->id;
            if (strpos($key, 'resume_') === 0) {
                //Upload Gambar Pemohon
                if ($request->hasFile('resume_applicant')) {
                    $resume_app = $request->file('resume_applicant');
                    $uploaded_file = $resume_app->store('public');
                    //Pecahkan
                    $paths = explode('/', $uploaded_file);
                    $filename = $paths[1];
                    //dd($uploaded_file);
                    //Save filename into Database
                    $appMeta->meta_key = $key;
                    $appMeta->meta_value = $filename;
                }
            }
            if(strpos($key, 'applicationmeta-trixFields') === 0){
                $addInfo = request('applicationmeta-trixFields');
                $appMeta->meta_key = 'applicant_add_info';
                $appMeta->meta_value = $addInfo['applicant_add_info'];

            } else {
                $appMeta->meta_key = $key;
                $appMeta->meta_value = $val;
            }
            $appMeta->save();
        }

        //Mail::to($app->metas[5]->meta_value)->send(new ApplicationSent($app));

        $message = "Your application has been successfully submitted. Your application number is: ".$app->apl_no;
        return redirect()->to('/')->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $apl_no = $request->get('apl_no');
        $apl = Application::where('apl_no', $apl_no)->get()->first();
        if($apl == null){
            return redirect()->to('/')->with('error', "Application cannot be found. Make sure your application number is correct.");
        }

        return view('application.view')->with(compact('apl'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    public function view_admin(Application $application){
        $apl = $application;
        $user = auth()->user();
        return view('admin.application.view')->with(compact('apl','user'));
    }

    public function process_admin(Application $application){
        $apl = $application;
        $user = auth()->user();
        return view('admin.application.process')->with(compact('apl','user'));
    }

    public function submit_process_admin(Request $request, Application $application){

        $user = auth()->user();
        $application->is_starred = $request->get('is_starred');
        $application->status = $request->get('status');
        $application->update();

        $apl = $application;

        return view('admin.application.view')->with(compact('apl','user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        //
    }
}
