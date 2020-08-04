<?php

use Illuminate\Support\Facades\Route;
use App\Vacancy;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = auth()->user();
    $vacancies = Vacancy::paginate(10);
    return view('welcome')->with(compact('vacancies','user'));
})->name('homepage');

Auth::routes([
'register' => false, // Registration Routes...
  'verify' => false, // Email Verification Routes...
]);

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/view/vacancy/{vacancy}', 'VacancyController@view')->name('view-vacancy');
Route::get('/vacancy/apply/{vacancy}', 'ApplicationController@create')->name('create-application');
Route::post('vacancy/apply/{vacancy}', 'ApplicationController@store')->name('submit-application');
Route::get('/view/application','ApplicationController@show')->name('view-application');
Route::get('/e-form/view/{apl_no}','ApplicationController@eform_show')->name('e-form');
Route::post('e-form','ApplicationController@eform_submit')->name('e-form-submit');


Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/vacancies','VacancyController@index_admin')->name('admin-view-vacancies');
    Route::get('/vacancies/create','VacancyController@create')->name('admin-create-vacancy');
    Route::post('vacancies/create','VacancyController@store')->name('admin-store-vacancy');
    Route::get('/vacancies/view/{vacancy}','VacancyController@view_admin')->name('admin-view-vacancy');
    Route::get('/vacancies/edit/{vacancy}','VacancyController@edit_admin')->name('admin-edit-vacancy');
    Route::get('/vacancies/delete/{vacancy}','VacancyController@destroy')->name('admin-delete-vacancy');
    Route::post('/vacancies/update/{vacancy}','VacancyController@update_admin')->name('admin-update-vacancy');

    Route::get('/applications','ApplicationController@index_admin')->name('admin-view-applications');
    Route::get('/application/view/{application}', 'ApplicationController@view_admin')->name('admin-view-application');
    Route::get('/application/process/{application}', 'ApplicationController@process_admin')->name('admin-process-application');
    Route::post('application/process/{application}', 'ApplicationController@submit_process_admin')->name('admin-submit-process-application');

    Route::get('/e-forms','ApplicationController@eform_index')->name('e-form-index');
    Route::get('/e-form/create','ApplicationController@eform_create')->name('e-form-create');
    Route::post('/e-form/generate','ApplicationController@eform_generate')->name('e-form-generate');

});

Route::group(['middleware' => 'auth'], function () {
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});

