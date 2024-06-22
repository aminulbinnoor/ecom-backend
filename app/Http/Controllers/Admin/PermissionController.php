<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Permission;

class PermissionController extends Controller
{
    public function create(Request $request)
    {
      $permissions = permissions();
      foreach ($permissions as $label_key => $lvalue) {
        Permission::updateOrCreate(
          [
          'name' => $label_key,
          'slug' => Str::slug($label_key,'-')
          ],
          [
          'name' => $label_key,
          'slug' => Str::slug($label_key,'-'),
          'label' => $label_key,
          ]);

        foreach ($lvalue as $key => $value) {
            Permission::updateOrCreate(
              [
              'name' => $value,
              'slug' => Str::slug($value,'-')
            ],
              [
              'name' => $value,
              'slug' => Str::slug($value,'-'),
              'label' => $label_key,
            ]);
        }


      }

      $msg = ['message' => 'Permission created', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function get(Request $request)
    {
      if ($request->id) {
          $Permission = Permission::where('id',$request->id)->first();
          $msg = ['message' => 'Permission retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$Permission),200);
      }elseif ($request->show_all) {
          $permission = Permission::get();
          $a = collect($permission)->groupBy('label')->toArray();
          $msg = ['message' => 'Permission retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$a),200);
       }

      $per_page = $request->per_page ?? 2;
      $Permission = Permission::paginate($per_page);
      $msg = ['message' => 'Permission retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$Permission),200);
    }

    public function update(Request $request)
    {
      // if(Permission::where('id','!=',$request->id)->where('id',$request->id)->exists()){
      //   $msg = ['message' => 'Permission Id already exits', 'status' => 'info', 'success' => false];
      //   return response()->json(output($msg,[]),200);
      // }
       $category = Permission::where('id',$request->id)
                            ->update([
                              'name' => $request->name,
                              'slug' => Str::slug($request->name,'-')
                            ]);
       $msg = ['message' => 'Permission updated', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function delete(Request $request)
    {
      $category = Permission::where('id',$request->id)
                           ->delete();
      $msg = ['message' => 'Permission Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }
}
