<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Employee;
use App\Model\RolePermission;
use App\Model\Permission;
class EmployeeController extends Controller
{
    public function create(Request $request)
    {
      if(Employee::where('employee_id',$request->employee_id)->exists()){
          $msg = ['message' => 'Employee id already exits', 'status' => 'info', 'success' => false];
          return response()->json(output($msg,[]),200);
      }
      $employee = new Employee;
      $employee->first_name = $request->first_name;
      $employee->employee_id = $request->employee_id;
      $employee->password = \bcrypt($request->password);
      $employee->role_id = $request->role_id;
      $employee->save();

      $msg = ['message' => 'Employee created', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function get(Request $request)
    {
      if ($request->id) {
          $employee = Employee::where('id',$request->id)->first();
          $msg = ['message' => 'Employee retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$employee),200);
      }elseif ($request->show_all) {
          $employee = Employee::where('is_super_admin',0)->all();
          $msg = ['message' => 'Employee retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$employee),200);
       }

      $per_page = $request->per_page ?? 2;
      $employee = Employee::with(['role'])->where('is_super_admin',0)->paginate($per_page);
      $msg = ['message' => 'Employee retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$employee),200);
    }

    public function update(Request $request)
    {
      if(Employee::where('id','!=',$request->id)->where('employee_id',$request->employee_id)->exists()){
        $msg = ['message' => 'Employee Id already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
      $submitData = [
          'first_name' => $request->first_name,
          'employee_id' => $request->employee_id,
          'mobile' => $request->mobile,
          'email' => $request->email,
          'role_id' => $request->role_id,
      ];

      if($request->new_password){
          $submitData['password'] = \bcrypt($request->new_password);
      }
       $category = Employee::where('id',$request->id)
                            ->update($submitData);
       $msg = ['message' => 'Employee updated', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function delete(Request $request)
    {
      $category = Employee::where('id',$request->id)
                           ->delete();
      $msg = ['message' => 'Employee Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function permissions(Request $request)
    {
        $permissions_id = RolePermission::where('role_id',auth_admin()->role_id)->pluck('permission_id')->toArray();
        $permissions = Permission::whereIn('id',$permissions_id)->pluck('slug')->toArray();
        $msg = ['message' => 'Employee permissions', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$permissions),200);
    }
}
