<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DontHavePermissionException;
use App\Http\Resources\EmployeeResource;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Manager;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EmployeeController extends Controller
{
    use ApiResponse , HasRoles;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign_role(Request $request)
    {
    }


    public function allEmployees(Request $request)
    {

        // $user =User::find($request->id);
        $user =auth()->user();

//        if ($user->can('list_employees'))
//        {
        if ($user)
        {
            $emp = Employee::all();
            $empResource = EmployeeResource::collection($emp);
            if(count($emp) > 0){
                return $this->sendJson($empResource);
            }else{
                throw new \App\Exceptions\NotFoundException;
        }

             }

             throw new \App\Exceptions\NotAuthorizeException;
    }

    public function addEmployee(Request $request)
    {

        // $user =User::find($request->id);
        $user =auth()->user();

//        if ($user->can('Add_employee'))
//        {

        if ($user)
        {
            $organization_id = Organization::find($request->organization);
            if ($organization_id) {
                $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password) ,
                'gender' => $request->gender,
                ]);

                $emp = Employee::create([
                'address' => $request->address,
                'education' => $request->education,
                'phone_no' => $request->phone_no,
                'user_id' => $user->id,
                // 'organization_id' => $organization_id->id,
                'organization_id' => $request->organization,
            ]);
            $user->assignRole('Employee');

                $empResource = new EmployeeResource($emp);

                if ($emp) {
                    return $this->sendJson($empResource);
                }
            }else{
                throw new \App\Exceptions\NotFoundOrganization;
            }
            throw new \App\Exceptions\FaildCreateException;
        }else{
            throw new \App\Exceptions\NotAuthorizeException;

             }

    }



    public function show(Request $request)
    {
        $user =auth()->user();
//        if ($user->can('show_employee_id'))
//        {
        if ($user)
        {
            $emp = Employee::find($request->emp_id);
//            if($emp){
//                return $this->sendJson(new EmployeeResource($emp));
//               }
//                throw new \App\Exceptions\NotFoundException;
//        }else{
//            throw new \App\Exceptions\NotAuthorizeException;
//
//             }
            if($emp){
//                return $this->sendJson(new EmployeeResource($emp));
                return $this->sendJson($emp);
            }
    }
    }




    public function destroy(Request $request)
    {
        // $user =User::find($request->id);
        $user =auth()->user();

//        if ($user->can('delete_employee'))
//        {
        if ($user)
        {
            $emp = Employee::find($request->emp_id);
//            dd($emp);
            $user = User::find($emp->user_id);
            $emp->delete();
            $user->delete();
            return $this->sendJson('employee deleed succefully');
        }else{
            throw new \App\Exceptions\NotAuthorizeException;

             }
    }



    public function update(Request $request)
    {

        // $user =User::find($request->id);
        $user =auth()->user();

//        if ($user->can('update_employee'))
//        {
        if ($user)
        {
            $request->validate([
                'address' => 'required',
                'education' => 'required',
//                'phone_no' => 'required',
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
//                'gender' => 'required',
            ]);

            // $user = User::find($request->usr_id);
            // $employee = Employee::where('user_id', $user->id)->first();
            // dd($user);
            $emp = Employee::find($request->emp_id);
//            dd($emp);
            $user = User::find($emp->user_id);
            $emp->update($request->all());

            $user->update($request->all());


            $user_new = new EmployeeResource ($emp);
            return $user_new;
        }else{
            throw new \App\Exceptions\NotAuthorizeException;

             }

    }

    public function list_employee()
    {
        $user = Auth::user();
//        dd($user);
//        if($user->can('list_employees')){
        if ($user)
        {
            $employee = Employee::get();
            return $this->sendJson($employee);
//            return EmployeeResource::collection($employee);
        }
//        else{
//            throw new DontHavePermissionException();
//        }


    }


    public function Upload(Request $request)
    {

        if ($file = $request->file('file')) {

            $destinationPath = 'uploads';
            $file->move($destinationPath, $file->getClientOriginalName());
            return response()->json([
                "success" => true,
                "message" => "File Successfully Uploaded",
                "file" => $file,
            ]);
        }

    }


    public function assign_employee_to_organization(Request $request)
    {

                // $user =User::find($request->id);
                $user =auth()->user();


//                if ($user->can('Add_employee')) {
        if ($user)
        {
                    $organization_id = Organization::find($request->organization);
                    if ($organization_id) {



                        $employee_id = Employee::find($request->eployee);
                        $employee_id->organization_id= $request->organization;
                        $employee_id->save();

                    //dd($organization_id);


                        return True;
                    } else {
                        throw new \App\Exceptions\NotFoundOrganization;

                    }
                }else{
                    throw new \App\Exceptions\NotAuthorizeException;

                     }

    }







    // public function index($id)
    // {

    //     // $test = User::where('id',$id)->with('User_type')->first();
    //     // dd($test) ;

    //     // $test = User::where('id',$id)->with('employee')->first();
    //     // dd($test) ;

    //     // $test= Organization::where('id',$id)->with('Manager')->first();
    //     // dd($test);

    //     // $test= Organization::where('id',$id)->with('Employees')->first();
    //     // dd($test);


    //     // $test= Task::where('id',$id)->with('Manager')->first();
    //     // dd($test);

    //     // $test= Task::where('id',$id)->with('employee')->first();
    //     // dd($test);

    //     // $test= User::where('id',$id)->with('Manager')->first();
    //     // dd($test);

    //     // $test= UserType::where('id',$id)->with('Users')->first();
    //     // dd($test);

    //     // $test= Employee::where('id',$id)->with('User')->first();
    //     // dd($test);

    //     // $test= Employee::where('id',$id)->with('Organization')->first();
    //     // dd($test);

    //     // $test= Employee::where('id',$id)->with('Tasks')->first();
    //     // dd($test);

    //     // $test= Manager::where('id',$id)->with('User')->first();
    //     // dd($test);

    //     // $test= Manager::where('id',$id)->with('membership')->first();
    //     // dd($test);

    //     // $test= Manager::where('id',$id)->with('Organizations')->first();
    //     // dd($test);

    //     // $test= Manager::where('id',$id)->with('Tasks')->first();
    //     // dd($test);

    //     // $test= Task::where('id',$id)->with('Employee')->first();
    //     // dd($test);

    //     // $test= Task::where('id',$id)->with('Manager')->first();
    //     // dd($test);

    //     // $test= Task::where('id',$id)->with('Organization')->first();
    //     // dd($test);

    // }


    // public function get_first()
    // {
    //     $emp=Employee::first();
    //    if($emp){
    //     return $this->sendJson(new EmployeeResource($emp));
    //    }
    //    throw new \App\Exceptions\NotFoundException;
    // }
}
