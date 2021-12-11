<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OrganizationController;

use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',  [UserController::class, 'login']);
Route::post('/register',  [UserController::class, 'register']);
Route::post('user/promotion',  [UserController::class, 'userPromotion']);
Route::post('user/demotion',  [UserController::class, 'userDemotion']);


Route::post('/send-sms',  [BlockController::class, 'sendSMS']);
Route::post('/add-user',  [BlockController::class, 'addUser']);
Route::post('/upload-image',  [BlockController::class, 'addImage']);


Route::get('/redirect/{driver}',[SocialLoginController::class,'redirectToSocial'])->name('login.social');
Route::get('/callback/{driver}',[SocialLoginController::class,'handleSocialCallback']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('tasks',  [TaskController::class, 'allTasks']);
    Route::get('task/show',  [TaskController::class, 'reviewTask']);
    Route::post('task/create',  [TaskController::class, 'createTask']);
    Route::post('task/edit',  [TaskController::class, 'updateTask']);
    Route::post('task/delete',  [TaskController::class, 'softDeleteTask']);
    Route::post('task/restore',  [TaskController::class, 'restoreTask']);
    Route::post('task/assign',  [TaskController::class, 'assignTask']);
    Route::post('task/assign',  [TaskController::class, 'assignTask']);
    Route::post('task/status',  [TaskController::class, 'changeTaskStatus']);

});

//Route::get('organizations',  [OrganizationController::class, 'getAllOrganization']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('show-all-organization',  [OrganizationController::class, 'index'])->name('show_all_organizations');
    Route::post('create-organization',  [OrganizationController::class, 'store'])->name('add_organization');
    Route::post('show-organization',  [OrganizationController::class, 'show'])->name('show_one_organization');
    Route::post('update-organization',  [OrganizationController::class, 'update'])->name('update_organizations');
    Route::post('delete-organization',  [OrganizationController::class, 'destroy'])->name('delete_organization');
    Route::post('activation-organization',  [AdminController::class, 'activation'])->name('activation_organization');
});




Route::get('test-api',  [MembershipController::class, 'test_api']); //Done only manager and admin can list it

// Start Moustafa Api
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('list-membership',  [MembershipController::class, 'list_membership']); //Done only manager and admin can list it
    Route::post('subscribe-membership',  [MembershipController::class, 'subscribe_membership']); //Done
    Route::get('list-manager',  [ManagerController::class, 'list_manager']); //Done only admin
    Route::get('list-employee',  [EmployeeController::class, 'list_employee']);
    // Assign Role to Managers And Employees
    Route::post('assign-role',  [EmployeeController::class, 'assign-role']);

});



// Finish Moustafa Api

Route::get('/test/{id}',[TaskController::class,'test']);
Route::get('/allTasks',[TaskController::class,'index']);





Route::middleware('auth:sanctum')->group(function () {

    // Employee Routes
    Route::post('/all-employees',[EmployeeController::class,'allEmployees']);
    Route::post('add_employee',  [EmployeeController::class, 'addEmployee']);
    Route::post('/employee',[EmployeeController::class,'show']);
    Route::post('/update',[EmployeeController::class,'update']);
    Route::post('/delete-employee',[EmployeeController::class,'destroy']);
    Route::post('/assign-employee-to-organization',[EmployeeController::class,'assign_employee_to_organization']);



    // Manager Routes
    Route::post('/add-manager-permission',[ManagerController::class,'addManager_permission']);
    Route::post('/assign-manager-to-organization',[ManagerController::class,'assign_manager_to_organization']);

});




