<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Http\Traits\ApiDesignTrait;

use App\Http\Traits\NotificationTrait;
use App\Http\Traits\UploadImageTrait;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Organization;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //

    use ApiDesignTrait;
    use NotificationTrait;
    use UploadImageTrait;

    private $task;
    private $manager;
    private $organization;
    private $employee;
    private $status;

    public function __construct(Task $task, Manager $manager, Organization $organization, Employee $employee, Status $status) {

        $this->task = $task;
        $this->manager = $manager;
        $this->organization = $organization;
        $this->employee = $employee;
        $this->status = $status;
    }


    public function allTasks()
    {
        $this->authorize('view_all_tasks', Task::class);
        $tasks = $this->task->get();
        if(!is_null($tasks)){
            return $this->ApiResponse(200, 'All Tasks', null, $tasks);
        }
        return $this->ApiResponse(400, 'No Task is Exist');
    }


    public function reviewTask(Request $request)
    {
        $this->authorize('view_specific_task', Task::class);
        $validation = Validator::make($request->all(), ['task_id' => 'required|exists:tasks,id']);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'Validation Error', $validation->errors());
        }
        $task = $this->task->with(['employee', 'manager'])->find($request->task_id);
        return $this->ApiResponse(200, 'Task Details', null, $task);
    }


    public function createTask(Request $request)
    {
        $this->authorize('create_task', Task::class);
        $user = auth('sanctum')->user();
//        dd($user);
        $validation = Validator::make($request->all(), [
            'title'       => 'required|string',
            'description' => 'required|string',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'employee_id' => 'required|exists:employees,id',
//            'manager_id' => 'required|exists:managers,id',
            'organization_id' => 'required|exists:organizations,id',
            'status_id' => 'required|exists:statuses,id',
            'parent_id' => 'nullable|exists:tasks,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'Validation Error', $validation->errors());
        }


        if($user->user_type == 1) {
            $task = $this->task->create([
                'title' => $request->title,
                'description' => $request->description,
                'employee_id' => $request->employee_id,
                'image' => $this->uploadImage($request, 'task_image'),
                'manager_id' => $request->manager_id,
                'organization_id' => $request->organization_id,
                'status_id' => $request->status_id,
                'parent_id' => $request->parent_id,
            ]);
//            dd($user);
        }
        elseif ($user->user_type == 2){
//            dd($user);
//            return($user->id);
//            $managerId = $user->whereHas('Manager', function($q, $user){
//                 $q->where('user_id', $user->id);
//            })->first();
            $manager = $user->with('manager')->first();
            $managerId = $manager->id;
//            dd($managerId)
            $task = $this->task->create([
                'title' => $request->title,
                'description' => $request->description,
                'employee_id' => $request->employee_id,
                'image' => $this->uploadImage($request, 'task_image'),
                'manager_id' => $managerId,
                'organization_id' => $request->organization_id,
                'status_id' => $request->status_id,
                'parent_id' => $request->parent_id,
            ]);


        }

//        $this->sendNotification($task->manager_id, 'New Task Added');

        return $this->apiResponse(200, 'Task created successfully', null, new TaskResource($task));
    }


    public function updateTask(Request $request)
    {
        $this->authorize('edit_task', Task::class);
        $user = auth('sanctum')->user();
//        dd($user);

        $validation = Validator::make($request->all(), ['task_id' => 'required|exists:tasks,id']);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'Validation Error', $validation->errors());
        }

        $task = $this->task->find($request->task_id);
//        dd($task);
        if($request->image){
            $this->deleteImage('task_image', $task->image);
            $task->update([
                'image' => $this->uploadImage($request, 'task_image')
            ]);
        }
        if($user->user_type == 1) {
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'employee_id' => $request->employee_id,
//                'image' => $request->image,
                'manager_id' => $request->manager_id,
                'organization_id' => $request->organization_id,
                'status_id' => $request->status_id,
                'parent_id' => $request->parent_id,
            ]);
//            dd($user);
        }
        elseif ($user->user_type == 2){
            $manager = $user->with('manager')->first();
            $managerId = $manager->id;
//            dd($managerId);
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'employee_id' => $request->employee_id,
//                'image' => $request->image,
                'manager_id' => $managerId,
                'organization_id' => $request->organization_id,
                'status_id' => $request->status_id,
                'parent_id' => $request->parent_id,
            ]);


        }
        return $this->apiResponse(200, 'Task updated successfully', null, new TaskResource($task));
    }

    public function softDeleteTask(Request $request)
    {
        $this->authorize('delete_task', Task::class);
        $validation = Validator::make($request->all(), ['task_id' => 'required|exists:tasks,id']);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'Validation Error', $validation->errors());
        }

        $task = $this->task->find($request->task_id);
        if (is_null($task)) {
            return $this->ApiResponse(400, 'No Task Found');
        }
        $this->deleteImage('task_image', $task->image);
        $task->delete();
        return $this->apiResponse(200,'Task deleted successfully');
    }


    public function restoreTask(Request $request)
    {
        $this->authorize('restore_task', Task::class);
        $validation = Validator::make($request->all(), ['task_id' => 'required|exists:tasks,id']);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'Validation Error', $validation->errors());
        }

        $task = Task::withTrashed()->find($request->task_id);
        if (!is_null($task->deleted_at)) {
            $task->restore();
            return $this->ApiResponse(200,'Task restored successfully');
        }
        return $this->ApiResponse(200,'Task already restored');
    }




    public function assignTask(Request $request)
    {
        $this->authorize('assign_task', Task::class);
        $validation = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'employee_id' => 'required|exists:employees,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'Validation Error', $validation->errors());
        }

        $employee = $this->employee->find($request->employee_id);
        $manager = $this->manager->find($request->manager_id);

        /*$task = $this->task->create([
            $request->all
        ]);*/
        $task = $this->task->with(['employee', 'manager'])->find($request->task_id);
        $task->update([
            'employee_id' => $request->employee_id
        ]);

        $employee = Employee::find($request->employee_id);
        $user = User::find($employee->user_id);

//        $this->sendNotification($user, 'Task Assign', 'You have anew challenge task');
        return $this->apiResponse(200, 'Task Assigned Successfully', null, $this->sendNotification($user, 'Task Assign', 'You have anew challenge task'));
    }


    public  function changeTaskStatus(Request $request){
        $this->authorize('changeTaskStatus', Task::class);
        $validation = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'status_id' => 'required|exists:statuses,id',
        ]);
        if ($validation->fails()) {
            return $this->ApiResponse(400, 'Validation Error', $validation->errors());
        }

        $task = $this->task->find($request->task_id);
//        dd($task);
        $taskEmployeeId = $task->employee_id;
//        dd($taskEmployeeId);
        $user = auth('sanctum')->user();
        $employee = $user->with('employee')->first();
        $employeeId = $employee->id;
//        dd($employeeId);
        if($taskEmployeeId == $employeeId){
            $task->update([
                'status_id' => $request->status_id,
            ]);
            return $this->ApiResponse(200, 'Task Status Updated Successfully', $task);
        }
        return $this->apiResponse(404, "Authorization Token Problem");
    }
}


