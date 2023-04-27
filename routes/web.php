<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return view('home');
    });
    Route::get('home', 'App\Http\Controllers\HomeController@index')->name('home');
    Route::get('/', 'App\Http\Controllers\HomeController@index');

    Route::get('invoices/to_pay', '\App\Http\Controllers\InvoiceController@to_pay')->name('invoices.to_pay');
    Route::get('invoices/to_pay/{id}', '\App\Http\Controllers\InvoiceController@submitpayment')->name('invoices.submitpayment');

    Route::get('banks/credit_card/{bank}', 'App\Http\Controllers\BankController@show_credit_card')->name('banks.show_credit_card');

    Route::put('notifications/mark_readed/{id}', 'App\Http\Controllers\NotificationController@mark_readed')->name('notifications.mark_readed');

    Route::resource('wallets', 'App\Http\Controllers\WalletController');
    Route::resource('banks', 'App\Http\Controllers\BankController');
    Route::resource('credit_parcels', 'App\Http\Controllers\CreditParcelsController');
    Route::resource('expenses', 'App\Http\Controllers\ExpenseController');
    Route::resource('budgets', 'App\Http\Controllers\BudgetController');
    Route::resource('incomes', 'App\Http\Controllers\IncomeController');
    Route::resource('user_groups', 'App\Http\Controllers\UserGroupController');
    Route::resource('transfers', '\App\Http\Controllers\TransferController');
    Route::resource('investments', '\App\Http\Controllers\InvestmentController');
    Route::resource('invoices', '\App\Http\Controllers\InvoiceController');
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::resource('notifications', 'App\Http\Controllers\NotificationController');
    Route::resource('categories', '\App\Http\Controllers\CategoryController');

    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
    Route::post('profile/settings', ['as' => 'profile.settings', 'uses' => 'App\Http\Controllers\ProfileController@settings']);
    Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
    Route::get('expenses/cancel_rec/{id}', 'App\Http\Controllers\ExpenseController@cancel_rec')->name('expenses.cancel_rec');
    Route::get('incomes/cancel_rec/{org_id}', 'App\Http\Controllers\IncomeController@cancel_rec')->name('incomes.cancel_rec');
    Route::get('incomes/confirm_recepit/{id}', 'App\Http\Controllers\IncomeController@confirm_recepit')->name('incomes.confirm_recepit');
    Route::get('user_groups/get_in/{group_id}/{user_id}', 'App\Http\Controllers\UserGroupController@GetInGroup')->name('user_groups.get_in');
    Route::get('user_groups/get_out/{group_id}/{user_id}', 'App\Http\Controllers\UserGroupController@GetOutGroup')->name('user_groups.get_out');
    Route::post('user_groups/remove_user/{group_id}/{user_id}', 'App\Http\Controllers\UserGroupController@remove_user')->name('user_groups.remove_user');
    Route::post('investments/insert_yield/{id}', 'App\Http\Controllers\InvestmentController@insert_yield')->name('investments.insert_yield');
    Route::post('budgets/default_budget', 'App\Http\Controllers\BudgetController@default_budget')->name('budgets.default_budget');
    Route::post('budgets/deactivate/{id}', 'App\Http\Controllers\BudgetController@disable')->name('budgets.disable');
    Route::post('budgets/reactivate/{id}', 'App\Http\Controllers\BudgetController@enable')->name('budgets.enable');
    Route::post('categories/add', 'App\Http\Controllers\CategoryController@add')->name('categories.add');
});
