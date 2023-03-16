<?php

namespace App\Http\Controllers;

use App\Application;
use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\ApplicationMeta;
use App\User;
use App\Country;
use App\ApplicationAttachment;
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
        $countries = Country::all();
        return view('vacancy.apply')->with(compact('vacancy','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Vacancy $vacancy)
    {
        //dd($request->all());

        $request->flash();
        $validator = Validator::make(
            $request->all(),
            ['resume_applicant.*' => 'mimes:pdf,docx,doc,jpg,jpeg,png|required|max:20000',]
        );

         // If validation fails, seek for specific error
         if ($validator->fails()) {
            $error = 'Error: <br>';
            //If no RESUME uploaded
            if($request->file('resume_applicant') === null){
                $error =  $error.'Please upload your resume.<br>';
            }
            else{
                // Loop through each uploaded file and check if it's in the correct format
                foreach($request->file('resume_applicant') as $file) {
                    if(strpos($file->getMimeType(), 'pdf') !==  0 || strpos($file->getMimeType(), 'docx') !==  0 || strpos($file->getMimeType(), 'doc') !==  0 ){
                        $error = $error.'Your uploaded file is not in the correct format.<br>';
                    }
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
                    foreach ($request->file('resume_applicant') as $file) {
            
                        $filename = $file->getClientOriginalName();
                        $uploaded_file = $file->store('public');
                        //Pecahkan
                        $paths = explode('/', $uploaded_file);
                        $filepath = $paths[1];
                        //dd($filename);
                        //Save filename into Database
                        $attachment = new ApplicationAttachment;
                        $attachment->application_id = $app->id;
                        $attachment->file_name = $filename;
                        $attachment->file_path = $filepath;
                        $attachment->save();
                    }
                    continue;
                    // $resume_app = $request->file('resume_applicant');
                    // $uploaded_file = $resume_app->store('public');
                    // //Pecahkan
                    // $paths = explode('/', $uploaded_file);
                    // $filename = $paths[1];
                    // //dd($filename);
                    // //Save filename into Database
                    // $appMeta->meta_key = $key;
                    // $appMeta->meta_value = $filename;
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
        $countries = Country::all();
        if($apl == null){
            return redirect()->to('/')->with('error', "Application cannot be found. Make sure your application number is correct.");
        }
        else if($apl->status == "Generated"){
            return redirect()->to('/')->with('error', "Application has not been completed. Make sure to fill up the e-Form for it to be processed.");
        }

        return view('application.view')->with(compact('apl','countries'));
    }

    public function eform_index(){
        $user = auth()->user();
        $eforms = Application::orderBy('created_at','DESC')->where('is_eform','Yes')->paginate(10);
        return view('admin.eform.index')->with(compact('user','eforms'));
    }

    public function eform_show($apl_no){
        $app = Application::where('apl_no', $apl_no)->first();
        //dd($app);
        $vacancies = Vacancy::where('is_active','Yes')->get();
        $countries = Country::all();
        return view('vacancy.eform')->with(compact('vacancies','app','countries'));
    }

    public function eform_create(){
        $vacancies = Vacancy::where('is_active','Yes')->get();
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
            ['resume_applicant.*' => 'mimes:pdf,docx,doc,jpg,jpeg,png|required|max:20000',]
        );

         // If validation fails, seek for specific error
         if ($validator->fails()) {
            $error = 'Error: <br>';
            //If RESUME not uploaded
            if($request->file('resume_applicant') === null){
                $error =  $error.'Please upload your resume.<br>';
            }
            else{
                // Loop through each uploaded file and check if it's in the correct format
                foreach($request->file('resume_applicant') as $file) {
                    if(strpos($file->getMimeType(), 'pdf') !==  0 || strpos($file->getMimeType(), 'docx') !==  0 || strpos($file->getMimeType(), 'doc') !==  0 ){
                        $error = $error.'Your uploaded file is not in the correct format.<br>';
                    }
                }
            }
            return redirect()->route('e-form', ['apl_no' => $request->get('apl_no')])->with('error', $error);
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
                    foreach ($request->file('resume_applicant') as $file) {
            
                        $filename = $file->getClientOriginalName();
                        $uploaded_file = $file->store('public');
                        //Pecahkan
                        $paths = explode('/', $uploaded_file);
                        $filepath = $paths[1];
                        //dd($filename);
                        //Save filename into Database
                        $attachment = new ApplicationAttachment;
                        $attachment->application_id = $app->id;
                        $attachment->file_name = $filename;
                        $attachment->file_path = $filepath;
                        $attachment->save();
                    }
                    continue;
                    // $resume_app = $request->file('resume_applicant');
                    // $uploaded_file = $resume_app->store('public');
                    // //Pecahkan
                    // $paths = explode('/', $uploaded_file);
                    // $filename = $paths[1];
                    // //dd($filename);
                    // //Save filename into Database
                    // $appMeta->meta_key = $key;
                    // $appMeta->meta_value = $filename;
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
        
        if($application->created_at < Carbon::parse('01-5-2021')){
        $inputFileName = './excel/apl_form.xlsx';
        } elseif($application->created_at > Carbon::parse('15-02-2023')){
            $inputFileName = './excel/apl_form_new_new.xlsx';
        } 
        else{
            $inputFileName = './excel/apl_form_new.xlsx';
        }

        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        if($application->created_at < Carbon::parse('01-5-2021')){
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
        //$spreadsheet->getActiveSheet()->setCellValue('B33', $application->metas[21]->meta_value." ".$application->metas[22]->meta_value);
        //Exp Salary
        //$spreadsheet->getActiveSheet()->setCellValue('G33', $application->metas[23]->meta_value." ".$application->metas[24]->meta_value);

        //Add info
        $spreadsheet->getActiveSheet()->setCellValue('A47', $application->metas[26]->meta_value);

        //Signature
        $spreadsheet->getActiveSheet()->setCellValue('A59', "This form is completed online");
        } elseif($application->created_at > Carbon::parse('15-02-2023')){
               //Set data into form
        //Job Title
        $spreadsheet->getActiveSheet()->setCellValue('C4', $application->vacancy->job_title);
        //Date
        $spreadsheet->getActiveSheet()->setCellValue('H4', Carbon::parse($application->created_at)->isoFormat('D MMM YYYY'));
        //Name
        $spreadsheet->getActiveSheet()->setCellValue('A8', $application->metas[1]->meta_value);
        //ID NO
        $spreadsheet->getActiveSheet()->setCellValue('E8', $application->metas[3]->meta_value);
        //Gender
        $spreadsheet->getActiveSheet()->setCellValue('G8', $application->metas[4]->meta_value);
        //Address
        $spreadsheet->getActiveSheet()->setCellValue('B9', $application->metas[10]->meta_value);
        //City
        $spreadsheet->getActiveSheet()->setCellValue('B10', $application->metas[11]->meta_value);
        //State
        $spreadsheet->getActiveSheet()->setCellValue('E10', $application->metas[12]->meta_value);
        //Postcode
        $spreadsheet->getActiveSheet()->setCellValue('H10', $application->metas[13]->meta_value);
        //Nationality
        $spreadsheet->getActiveSheet()->setCellValue('B11', $application->metas[2]->meta_value);
        //DOB
        $spreadsheet->getActiveSheet()->setCellValue('F11', $application->metas[8]->meta_value);
        //Email
        $spreadsheet->getActiveSheet()->setCellValue('A13', $application->metas[5]->meta_value);
        //Mobile No
        $spreadsheet->getActiveSheet()->setCellValue('C13', $application->metas[6]->meta_value);
        //Office No
        $spreadsheet->getActiveSheet()->setCellValue('E13', $application->metas[7]->meta_value);
        //Marital Status
        $spreadsheet->getActiveSheet()->setCellValue('G13', $application->metas[9]->meta_value);
        //Health Conditions
        $spreadsheet->getActiveSheet()->setCellValue('A17', $application->metas[14]->meta_value);
        //Is pregnant
        $spreadsheet->getActiveSheet()->setCellValue('D20', $application->metas[15]->meta_value);

        //Referee 1 Name
        $spreadsheet->getActiveSheet()->setCellValue('B28', $application->metas[16]->meta_value);
        //Referee 1 Tel No
        $spreadsheet->getActiveSheet()->setCellValue('B29', $application->metas[17]->meta_value);
        //Referee 1 Occupation
        $spreadsheet->getActiveSheet()->setCellValue('B30', $application->metas[18]->meta_value);
        //Referee 1 Years Known
        $spreadsheet->getActiveSheet()->setCellValue('B31', $application->metas[19]->meta_value);

        //Referee 2 Name
        $spreadsheet->getActiveSheet()->setCellValue('G28', $application->metas[20]->meta_value);
        //Referee 3 Tel No
        $spreadsheet->getActiveSheet()->setCellValue('G29', $application->metas[21]->meta_value);
        //Referee 4 Occupation
        $spreadsheet->getActiveSheet()->setCellValue('G30', $application->metas[22]->meta_value);
        //Referee 5 Years Known
        $spreadsheet->getActiveSheet()->setCellValue('G31', $application->metas[23]->meta_value);

        //Willing to travel
        $spreadsheet->getActiveSheet()->setCellValue('B34', $application->metas[24]->meta_value);
        //Notice Period
        $spreadsheet->getActiveSheet()->setCellValue('G34', $application->metas[25]->meta_value." ".$application->metas[26]->meta_value);
        //Curr Salary
        //$spreadsheet->getActiveSheet()->setCellValue('B35', $application->metas[25]->meta_value." ".$application->metas[26]->meta_value);
        //Exp Salary
        //$spreadsheet->getActiveSheet()->setCellValue('G35', $application->metas[27]->meta_value." ".$application->metas[28]->meta_value);

        //Add info
        $spreadsheet->getActiveSheet()->setCellValue('A51', $application->metas[32]->meta_value);

        //Signature
        $spreadsheet->getActiveSheet()->setCellValue('A62', "This form is completed online");
        }
        else{
            //Set data into form
        //Job Title
        $spreadsheet->getActiveSheet()->setCellValue('C4', $application->vacancy->job_title);
        //Date
        $spreadsheet->getActiveSheet()->setCellValue('H4', Carbon::parse($application->created_at)->isoFormat('D MMM YYYY'));
        //Name
        $spreadsheet->getActiveSheet()->setCellValue('A8', $application->metas[1]->meta_value);
        //ID NO
        $spreadsheet->getActiveSheet()->setCellValue('E8', $application->metas[2]->meta_value);
        //Gender
        $spreadsheet->getActiveSheet()->setCellValue('G8', $application->metas[3]->meta_value);
        //Address
        $spreadsheet->getActiveSheet()->setCellValue('B9', $application->metas[8]->meta_value);
        //City
        $spreadsheet->getActiveSheet()->setCellValue('B10', $application->metas[9]->meta_value);
        //State
        $spreadsheet->getActiveSheet()->setCellValue('E10', $application->metas[10]->meta_value);
        //Postcode
        $spreadsheet->getActiveSheet()->setCellValue('H10', $application->metas[11]->meta_value);
        //Email
        $spreadsheet->getActiveSheet()->setCellValue('A12', $application->metas[5]->meta_value);
        //Mobile No
        $spreadsheet->getActiveSheet()->setCellValue('C12', $application->metas[6]->meta_value);
        //Office No
        $spreadsheet->getActiveSheet()->setCellValue('E12', $application->metas[7]->meta_value);
        //Marital Status
        $spreadsheet->getActiveSheet()->setCellValue('G12', $application->metas[4]->meta_value);
        //Health Conditions
        $spreadsheet->getActiveSheet()->setCellValue('A16', $application->metas[12]->meta_value);
        //Is pregnant
        $spreadsheet->getActiveSheet()->setCellValue('D19', $application->metas[13]->meta_value);

        //Referee 1 Name
        $spreadsheet->getActiveSheet()->setCellValue('B27', $application->metas[14]->meta_value);
        //Referee 1 Tel No
        $spreadsheet->getActiveSheet()->setCellValue('B28', $application->metas[15]->meta_value);
        //Referee 1 Occupation
        $spreadsheet->getActiveSheet()->setCellValue('B29', $application->metas[16]->meta_value);
        //Referee 1 Years Known
        $spreadsheet->getActiveSheet()->setCellValue('B30', $application->metas[17]->meta_value);

        //Referee 2 Name
        $spreadsheet->getActiveSheet()->setCellValue('G27', $application->metas[18]->meta_value);
        //Referee 3 Tel No
        $spreadsheet->getActiveSheet()->setCellValue('G28', $application->metas[19]->meta_value);
        //Referee 4 Occupation
        $spreadsheet->getActiveSheet()->setCellValue('G29', $application->metas[20]->meta_value);
        //Referee 5 Years Known
        $spreadsheet->getActiveSheet()->setCellValue('G30', $application->metas[21]->meta_value);

        //Willing to travel
        $spreadsheet->getActiveSheet()->setCellValue('B33', $application->metas[22]->meta_value);
        //Notice Period
        $spreadsheet->getActiveSheet()->setCellValue('G33', $application->metas[23]->meta_value." ".$application->metas[24]->meta_value);
        //Curr Salary
        //$spreadsheet->getActiveSheet()->setCellValue('B35', $application->metas[25]->meta_value." ".$application->metas[26]->meta_value);
        //Exp Salary
        //$spreadsheet->getActiveSheet()->setCellValue('G35', $application->metas[27]->meta_value." ".$application->metas[28]->meta_value);

        //Add info
        $spreadsheet->getActiveSheet()->setCellValue('A49', $application->metas[30]->meta_value);

        //Signature
        $spreadsheet->getActiveSheet()->setCellValue('A61', "This form is completed online");
        }
        


        $file_name = str_replace(' ', '_', $application->metas[1]->meta_value);
        $file_name = $file_name."_form.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment; filename="'. urlencode($file_name).'"');
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
