<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'Site\HomeController@index')->name('home');

Route::prefix('painel')->group(function(){
    Route::get('/', 'Admin\HomeController@index')->name("admin");

    Route::get('/dashboard', 'Admin\DashboardController@index')->name("dashboard");
    
    Route::get('login', 'Admin\Auth\LoginController@index')->name("login");
    Route::post("login", "Admin\Auth\LoginController@authenticate");

    Route::get('register', 'Admin\Auth\RegisterController@index')->name("register");
    Route::post("register", "Admin\Auth\RegisterController@register");

    Route::get('password/reset', 'Admin\Auth\ResetPasswordController@request')->name("password.request");
    Route::post('password/email', "Admin\Auth\ResetPasswordController@email")->name("password.email");
    Route::get('password/reset-password/{token}', "Admin\Auth\ResetPasswordController@reset")->name("password.reset");
    Route::post('password/reset/', "Admin\Auth\ResetPasswordController@update")->name("password.update");

    Route::post("logout", "Admin\Auth\LoginController@logout")->name("logout");

    Route::resource('users', 'Admin\UserController');

    Route::resource('pages', 'Admin\PageController');

    Route::resource('modules', 'Admin\ModuleController');
    Route::get('modules/fav/{module}', 'Admin\ModuleController@toggleFavorite')->name("togglefav");
    Route::get('modules/practice/{module}', 'Admin\ModuleController@practice')->name("modules.practice");
    Route::post('/modules/search', 'Admin\ModuleController@searchDB')->name("modulessearch");

    Route::resource('cards', 'Admin\CardController');
    Route::post('cards/imgstore', 'Admin\CardController@imgstore')->name("cards.imgstore");
    Route::post('cards/txtstore', 'Admin\CardController@txtstore')->name("cards.txtstore");
    Route::get('cards/next/{module}', 'Admin\CardController@queueNext')->name("queuenext");
    Route::post('cards/solve', 'Admin\CardController@updateElo')->name("cardsolve");

    Route::resource('tags', 'Admin\TagController');
    Route::get("tags/new/{module}", 'Admin\TagController@create')->name("tags.create");

    Route::resource('groups', 'Admin\GroupController');
    Route::get('groups/clink/{group}', 'Admin\GroupController@genlink')->name("groups.genlink");
    Route::get('groups/join/{link}', 'Admin\GroupController@join')->name("groups.join");
    Route::post('groups/join/{group}', 'Admin\GroupController@joinMember')->name("groups.joinMember");
    Route::delete('groups/remove/{user}/{group}', 'Admin\GroupController@removeMember')->name("groups.removeMember");
    Route::get('groups/{group}/module/{module}', 'Admin\GroupController@addModule')->name("groups.addModule");
    Route::post('groups/msg/{group}', 'Admin\GroupController@writeMsg')->name("groups.writeMsg");

    Route::get('profile', 'Admin\ProfileController@index')->name("profile");
    Route::put('profile/save', 'Admin\ProfileController@save')->name("profile.save");

    Route::get('settings', 'Admin\SettingController@index')->name("settings");
    Route::put('settings/save', 'Admin\SettingController@save')->name("settings.save");

    Route::get('chess', 'Admin\ChessController@index')->name("chess");

    Route::get('search', 'Admin\ModuleController@search');
    Route::post('search', 'Admin\ModuleController@search2');
});

Route::get('oauth/facebook', 'Admin\FacebookController@redirectToFacebook')->name('facebook.login');
Route::get('oauth/facebook/callback', 'Admin\FacebookController@facebookSignIn')->name('facebook.callback');
Route::post('oauth/facebook/datadelete', 'Admin\FacebookController@dataDeletionCallback')->name('facebook.dataDeletion');
Route::get('oauth/facebook/datadelete/{code}', 'Admin\FacebookController@dataDeletionStatus')->name('facebook.dataDeletionStatus');


Route::fallback("Site\PageController@index");