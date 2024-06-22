<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Role;

class RoleController extends Controller
{
    public function create(Request $request)
    {
      if(Role::where('name',$request->name)->exists()){
          $msg = ['message' => 'Role already exits', 'status' => 'info', 'success' => false];
          return response()->json(output($msg,[]),200);
      }
      $role = new Role;
      $role->name = $request->name;
      $role->description = $request->description;
      $role->slug = Str::slug($request->name,'-');
      $role->save();

      $msg = ['message' => 'Role created', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function get(Request $request)
    {
      if ($request->id) {
          $role = Role::where('id',$request->id)->first();
          $msg = ['message' => 'Role retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$role),200);
      }elseif ($request->show_all) {
          $role = Role::all();
          $msg = ['message' => 'Role retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$role),200);
       }

      $per_page = $request->per_page ?? 2;
      $role = Role::paginate($per_page);
      $msg = ['message' => 'Role retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$role),200);
    }

    public function update(Request $request)
    {
      // if(Role::where('id','!=',$request->id)->where('role_id',$request->id)->exists()){
      //   $msg = ['message' => 'Role Id already exits', 'status' => 'info', 'success' => false];
      //   return response()->json(output($msg,[]),200);
      // }
       $category = Role::where('id',$request->id)
                            ->update([
                              'name' => $request->name,
                              'description' => $request->description,
                              'slug' => Str::slug($request->name,'-')
                            ]);
       $msg = ['message' => 'Role updated', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function delete(Request $request)
    {
      $category = Role::where('id',$request->id)
                           ->delete();
      $msg = ['message' => 'Role Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function setPermission(Request $request)
    {
      RolePermission::where('role_id',$request->role_id)->delete();
      foreach ($request->permissions as $key => $value) {
        RolePermission::updateOrCreate(
          ['role_id' => $request->role_id,'permission_id' => $value],
          ['role_id' => $request->role_id,'permission_id' => $value]
         );
      }
      $msg = ['message' => 'Permission assigned', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
    }

    public function getPermission(Request $request)
    {
      $getPermissionid = RolePermission::where('role_id',$request->role_id)->pluck('permission_id')->toArray();
      $msg = ['message' => 'Permission get', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$getPermissionid),200);
    }
}
