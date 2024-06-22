<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Model\Admin;

class LoginController extends Controller
{

  /**
   * Dummy super admin create.
   *
   * @param first_name string
   * @param employee_id string
   * @param password string
   * @param is_super_admin boolean
   * @author mostafa
   * @since 09-16-2020
   * @return json
   */

    public function dummySp()
    {
        // !Admin::where('employee_id','10')->exists()
      if(true){
        $admin = Admin::forceCreate([
          'first_name' => 'kamal',
          'employee_id' => '11',
          'password' => bcrypt('12345678'),
          'is_super_admin' => true
        ]);
        return response()->json($admin);
      }
        return response()->json(['Already have this employee']);

    }

    public function login(Request $request)
    {
      $admin = Admin::where('email',$request->username)
                    ->orWhere('employee_id',$request->username)
                    ->orWhere('mobile',$request->username)
                    ->first();
      if ($admin) {
        if (Hash::check($request->password, $admin->password)) {
          $key = "p2p";
          $payload = array(
              "iss" => "http://example.org",
              "aud" => "http://example.com",
              "iat" => 1356999524,
              "nbf" => 1357000000,
              'id'  => $admin->id
          );
          $msg = ['message' => 'Authentication successful', 'status' => 'info', 'success' => true];
          $data = [
            'admin' => $admin,
            'jwt_token' => JWT::encode($payload, $key)
          ];
          return response()->json(output($msg,$data),200);
        }else{
          $msg = ['message' => 'Your credentials is wrong', 'status' => 'info', 'success' => false];
          return response()->json(output($msg,[]),200);
        }
      }else{
        $msg = ['message' => 'Your credentials is wrong', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }

    }
}
