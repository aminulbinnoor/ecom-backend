<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Model\User;

class LoginController extends Controller
{
  public function login(Request $request)
    {
        $user = User::where('email',$request->username)
                      ->orWhere('phone',$request->username)
                      ->first();
        if ($user) {
          if (Hash::check($request->password, $user->password)) {
            $key = "p2p";
            $payload = array(
                "iss" => "http://example.org",
                "aud" => "http://example.com",
                "iat" => 1356999524,
                "nbf" => 1357000000,
                'id'  => $user->id
            );
            $msg = ['message' => 'Authentication successful', 'status' => 'info', 'success' => true];
            $data = [
              'user' => $user,
              'jwt_token' => JWT::encode($payload, $key)
            ];
            return response()->json(output($msg,$data),200);
          }else{
            $msg = ['message' => 'Your credentials is wrong', 'status' => 'info', 'success' => false];
            return response()->json(output($msg,[]),200);
          }
        }else{
          $msg = ['message' => 'Your credentials is wrong', 'status' => 'info', 'success' => false];
          return response()->json(output($msg,[]),401);
        }

    }

    /**
     * Otp sent to signup user
     * @param $request Illuminate\Http\Request;
     * @return json
     */
    public function signupOtp(Request $request)
    {

        if(!User::where('phone',$request->mobile)->exists()){
            $otp = otp_send($request->mobile);

            mobile_msg($request->mobile,"Your otp is $otp, It will be expired after 10 min. P2P");

            $msg = ['message' => "otp sent to - $request->mobile", 'status' => 'info', 'success' => true];
            return response()->json(output($msg,$otp),200);
        }else{
            $msg = ['message' => "User already exists.you can login", 'status' => 'error', 'success' => false];
            return response()->json(output($msg,[]),200);
        }

    }

    /**
     * Otp verification
     * @param $request Illuminate\Http\Request;
     * @return json
     */
    public function signupOtpCheck(Request $request)
    {
        $otpVeirfy = otp_check($request->mobile,$request->otp);

        if($otpVeirfy){
            if(!User::where('phone',$request->mobile)->exists()){
                $user = new User;
                $user->first_name = $request->first_name;
                $user->last_name = $request->first_name;
                $user->email = $request->email;
                $user->phone = $request->mobile;
                $user->password = bcrypt($request->password);
                $user->save();

                if($user->email){
                    send_mail($user->email,'email.user.register', "Welcome to P2P Family",['user' => $user]);
                }
                if($user->phone){
                    mobile_msg($user->phone,
                    "Dear Valued Customer,\n We are delighted to have you as a member of P2P family. Your account has been created at https://www.p2p.com.bd");
                }

                $msg = ['message' => "Registration done", 'status' => 'error', 'success' => true];
            }else{
                $msg = ['message' => "User already exists.you can login", 'status' => 'error', 'success' => false];
            }
        }else{
            $msg = ['message' => "Wrong otp or expired otp", 'status' => 'error', 'success' => false];
        }

        return response()->json(output($msg,$otpVeirfy),200);
    }
}
