<?php

namespace App\Http\Controllers;

use App\Application;
use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\ApplicationMeta;
use App\User;
use App\ApplicationLog;
use Mail;
use App\Mail\ApplicationSent;
use App\Mail\NewApplication;
use App\Mail\EformGenerated;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $applications = Application::orderBy('created_at','DESC')->where('is_eform','No')->paginate(10);
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

            //If resume uploaded in wrong format
            if($request->hasFile('resume_applicant') == true){
                if(strpos($request->file('resume_applicant')->getMimeType(), 'pdf') !==  0 || strpos($request->file('resume_applicant')->getMimeType(), 'docx') !==  0 || strpos($request->file('resume_applicant')->getMimeType(), 'doc') !==  0 ){
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
                //Upload resume
                if ($request->hasFile('resume_applicant')) {
                    $resume_app = $request->file('resume_applicant');
                    $uploaded_file = $resume_app->store('public');
                    //Pecahkan
                    $paths = explode('/', $uploaded_file);
                    $filename = $paths[1];
                    //dd($filename);
                    //Save filename into Database
                    $appMeta->meta_key = $key;
                    $appMeta->meta_value = $filename;
                }
            }
            // else if(strpos($key, 'applicationmeta-trixFields') === 0){
            //     $addInfo = request('applicationmeta-trixFields');
            //     $appMeta->meta_key = 'applicant_add_info';
            //     $appMeta->meta_value = $addInfo['applicant_add_info'];
            //     //$appMeta->update(['applicationmeta-trixFields' => request('applicationmeta-trixFields')]);
            // }
            else {
                $appMeta->meta_key = $key;
                $appMeta->meta_value = $val;
            }
            $appMeta->save();
        }

        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new NewApplication($app));
        }
        Mail::to($app->metas[5]->meta_value)->send(new ApplicationSent($app));

        $message = "Your application has been successfully submitted. Your application number is:";
        $apl_num = $app->apl_no;
        return redirect()->to('/')->with(['message' => $message, 'apl_num' => $apl_num]);
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
        else if($apl->status == "Generated"){
            return redirect()->to('/')->with('error', "Application has not been completed. Make sure to fill up the e-Form for it to be processed.");
        }

        return view('application.view')->with(compact('apl'));
    }

    public function eform_index(){
        $user = auth()->user();
        $eforms = Application::orderBy('created_at','DESC')->where('is_eform','Yes')->paginate(10);
        return view('admin.eform.index')->with(compact('user','eforms'));
    }

    public function eform_show($apl_no){
        $app = Application::where('apl_no', $apl_no)->first();
        //dd($app);
        $vacancies = Vacancy::all();
        return view('vacancy.eform')->with(compact('vacancies','app'));
    }

    public function eform_create(){
        $vacancies = Vacancy::all();
        $user = auth()->user();
        return view('admin.eform.create')->with(compact('vacancies','user'));
    }

    public function eform_generate(Request $request){

        $eforms = Application::orderBy('created_at','DESC')->where('is_eform','Yes')->paginate(10);
        $app = new Application;
        $app->vacancy_id = $request->applied_for;

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
        $vac_id = $request->applied_for;
        //Combine all
        $app->apl_no = $vac_id.$dt.$timestmp.$app_id;
        $app->is_eform = "Yes";
        $app->status = "Generated";
        $app->save();

        Mail::to($request->applicant_email)->send(new EformGenerated($app));
        $message = "Form has been successfully generated. An email has been sent to the applicant. Alternatively, share this link to the applicant to access the form: ".url('/e-form/view').'/'.$app->apl_no;
        return redirect()->route('e-form-index')->with('message',$message);
    }

    public function eform_submit(Request $request){
        //dd($request->all());
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
                if(strpos($request->file('resume_applicant')->getMimeType(), 'pdf') !==  0 || strpos($request->file('resume_applicant')->getMimeType(), 'docx') !==  0 || strpos($request->file('resume_applicant')->getMimeType(), 'doc') !==  0 ){
                    $error = $error.'Your uploaded file is not in the correct format.';
                }
            }
            return redirect()->route('create-application', ['vacancy' => $vacancy])->with('error', $error);
        }

        $inputs = $request->all();
        $apl_no = $request->get('apl_no');
        $app =  Application::where('apl_no', $apl_no)->first();

        $app->status = "Called";
        $app->is_eform = "Yes";
        $app->save();

        foreach ($inputs as $key => $val) {
            if (strpos($key, '_token') === 0 || strpos($key, 'attachment') === 0 || strpos($key, 'apl_no') === 0) {
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
                    //dd($filename);
                    //Save filename into Database
                    $appMeta->meta_key = $key;
                    $appMeta->meta_value = $filename;
                }
            }
            // else if(strpos($key, 'applicationmeta-trixFields') === 0){
            //     $addInfo = request('applicationmeta-trixFields');
            //     $appMeta->meta_key = 'applicant_add_info';
            //     $appMeta->meta_value = $addInfo['applicant_add_info'];
            //     //$appMeta->update(['applicationmeta-trixFields' => request('applicationmeta-trixFields')]);
            // }
            else {
                $appMeta->meta_key = $key;
                $appMeta->meta_value = $val;
            }
            $appMeta->save();
        }

        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new NewApplication($app));
        }
        Mail::to($app->metas[5]->meta_value)->send(new ApplicationSent($app));

        $message = "Your form has been successfully submitted. Your application number is: ".$app->apl_no;
        return redirect()->to('/')->with('message', $message);
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

        $log = new ApplicationLog;
        $log->user_id = $user->id;
        $log->application_id = $application->id;
        $log->action = "viewed";
        $log->save();

        $apl_log_viewed = ApplicationLog::orderBy('created_at', 'desc')->where('application_id',$application->id)->where('action','viewed')->skip(1)->take(1)->first();
        $apl_log_processed = ApplicationLog::where('application_id',$application->id)->where('action','processed')->latest()->first();
        $apl_log_called = ApplicationLog::where('application_id',$application->id)->where('action','called')->latest()->first();
        $apl_log_starred = ApplicationLog::where('application_id',$application->id)->where('action','starred')->latest()->first();
        $apl_log_accepted = ApplicationLog::where('application_id',$application->id)->where('action','accepted')->latest()->first();
        $apl_log_denied = ApplicationLog::where('application_id',$application->id)->where('action','denied')->latest()->first();
        return view('admin.application.view')->with(compact('apl','user','log','apl_log_viewed','apl_log_processed','apl_log_called','apl_log_starred','apl_log_accepted','apl_log_denied'));
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

        $log = new ApplicationLog;
        $log->user_id = $user->id;
        $log->application_id = $application->id;
        if($application->status == "Processed"){
            $log->action = "processed";
        }
        else if ($application->status == "Called"){
            $log->action = "called";
        }
        else if($application->status == "Accepted"){
            $log->action = "accepted";
        }
        else if($application->status == "Denied"){
            $log->action = "denied";
        }
        $log->save();

        if($application->is_starred == "Yes"){
            $log = new ApplicationLog;
            $log->user_id = $user->id;
            $log->application_id = $application->id;
            $log->action = "starred";
            $log->save();
        }

        return redirect()->route('admin-view-application',$application);
    }

    public function export_admin(Application $application){
        $inputFileName = './excel/apl_form.xlsx';

        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        //Set data into form
        //Job Title
        $spreadsheet->getActiveSheet()->setCellValue('C4', $application->vacancy->job_title);
        //Date
        $spreadsheet->getActiveSheet()->setCellValue('H4', Carbon::parse($application->created_at)->isoFormat('D MMM YYYY'));
        //Name
        $spreadsheet->getActiveSheet()->setCellValue('A8', $application->metas[1]->meta_value);
        //ID NO
        $spreadsheet->getActiveSheet()->setCellValue('F8', $application->metas[2]->meta_value);
        //Gender
        $spreadsheet->getActiveSheet()->setCellValue('G8', $application->metas[3]->meta_value);
        //Email
        $spreadsheet->getActiveSheet()->setCellValue('A10', $application->metas[5]->meta_value);
        //Mobile No
        $spreadsheet->getActiveSheet()->setCellValue('C10', $application->metas[6]->meta_value);
        //Office No
        $spreadsheet->getActiveSheet()->setCellValue('E10', $application->metas[7]->meta_value);
        //Marital Status
        $spreadsheet->getActiveSheet()->setCellValue('G10', $application->metas[4]->meta_value);
        //Health Conditions
        $spreadsheet->getActiveSheet()->setCellValue('A14', $application->metas[8]->meta_value);
        //Is pregnant
        $spreadsheet->getActiveSheet()->setCellValue('D17', $application->metas[9]->meta_value);

        //Referee 1 Name
        $spreadsheet->getActiveSheet()->setCellValue('B25', $application->metas[10]->meta_value);
        //Referee 1 Tel No
        $spreadsheet->getActiveSheet()->setCellValue('B26', $application->metas[11]->meta_value);
        //Referee 1 Occupation
        $spreadsheet->getActiveSheet()->setCellValue('B27', $application->metas[12]->meta_value);
        //Referee 1 Years Known
        $spreadsheet->getActiveSheet()->setCellValue('B28', $application->metas[13]->meta_value);

        //Referee 2 Name
        $spreadsheet->getActiveSheet()->setCellValue('G25', $application->metas[14]->meta_value);
        //Referee 3 Tel No
        $spreadsheet->getActiveSheet()->setCellValue('G26', $application->metas[15]->meta_value);
        //Referee 4 Occupation
        $spreadsheet->getActiveSheet()->setCellValue('G27', $application->metas[16]->meta_value);
        //Referee 5 Years Known
        $spreadsheet->getActiveSheet()->setCellValue('G28', $application->metas[17]->meta_value);

        //Willing to travel
        $spreadsheet->getActiveSheet()->setCellValue('B31', $application->metas[18]->meta_value);
        //Notice Period
        $spreadsheet->getActiveSheet()->setCellValue('G31', $application->metas[19]->meta_value." ".$application->metas[20]->meta_value);
        //Curr Salary
        $spreadsheet->getActiveSheet()->setCellValue('B33', $application->metas[21]->meta_value." ".$application->metas[22]->meta_value);
        //Exp Salary
        $spreadsheet->getActiveSheet()->setCellValue('G33', $application->metas[23]->meta_value." ".$application->metas[24]->meta_value);

        //Add info
        $spreadsheet->getActiveSheet()->setCellValue('A47', $application->metas[26]->meta_value);

        //Signature
        $spreadsheet->getActiveSheet()->setCellValue('A59', "This form is completed online");


        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Leave_Applications_All.xlsx"');
        $writer->save("php://output");
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
