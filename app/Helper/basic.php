<?php
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Notifications\MailNotification;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Redis;
use \Firebase\JWT\JWT;
use App\Excel\Export;
use App\Model\Admin;
use App\Model\User;
use App\Model\Otp;

include __DIR__.'/permissions.php';

if (!function_exists('output')) {
  function output($msg,$data) {
    $output = [
      'msg' => $msg,
      'data' => $data
    ];
    return $output;
  }
}

if (!function_exists('auth_id')) {
    function auth_id() {
        $key = "p2p";
        try {
            return JWT::decode(request()->header('jwt'),$key,array('HS256'))->id;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}

if (!function_exists('auth_admin')) {
    function auth_admin() {
        $admin = Admin::where('id',auth_id())->first();
        return $admin;
    }
}

if (!function_exists('auth_user')) {
    function auth_user() {
        $user = User::where('id',auth_id())->first();
        return $user;
    }
}

if (!function_exists('image_upload_size')) {
    function image_upload_size() {
        $sizes = array(
            'mdpi'  => ['w' => 480, 'h' => 320],
            'hdpi'  => ['w' => 960, 'h' => 640],
            'xhdpi' => ['w' => 1040, 'h' => 720],
        );
        return $sizes;
    }
}

if (!function_exists('image_upload_base64')) {
    function image_upload_base64($folderName,$fileName,$file,$driver="local") {
        ini_set('post_max_size', '20000M');
        ini_set('upload_max_filesize', '20000M');
        ini_set('memory_limit','256M');
        $sizes = image_upload_size();
        foreach ($sizes as $key => $size) {
            // $imgData = Image::make($file)->resize($size['w'], NULL, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // });
            //
            // $thumb = $imgData->stream()->__toString();

            // $b = $thumb->destroy();
            try {
                Storage::disk($driver)->put($folderName.'/'.$key.'/'.$fileName, \File::get($file), 'public');
                // $a = $imgData->destroy();

            } catch (\Exception $e) {
                dump($e->getMessage());
            }


        }

    }
}


if (!function_exists('send_mail()')) {
    function send_mail($email,$template,$subject,$emailData=[]) {
        \Notification::route('mail',$email)->notify(
            (new MailNotification(
                $template,
                $subject,
                $emailData
            ))->delay(now()->addMinutes(1))
        );
    }
}

if (!function_exists('otp_send')) {
    function otp_send($mobile)
    {
        $otp = (string) mt_rand(100000,999999);
        $storeOtp = Otp::updateOrCreate(['phone' => $mobile],['mobile' => $mobile, 'otp' => $otp, 'verified' => 0]);
        if ($storeOtp) {
            return $otp;
        }
    }
}

if (!function_exists('otp_check')) {
    function otp_check($mobile,$otp)
    {
        $otp = Otp::where('phone',$mobile)
                    ->where('otp',$otp)
                    ->first();
        if($otp) {
            if($otp->verified == true) {
                return false;
            }else{
                $otp->verified = true;
                $otp->save();
                return true;
            }
        }else{
            return false;
        }
    }
}

if (!function_exists('p2p_drive')) {
    function p2p_drive()
    {
        return 's3';
    }
}

if (!function_exists('p2p_set_id')) {
    function p2p_set_id($id,$type,$zero=6)
    {
        return $type.sprintf('%0'.$zero.'s', $id);
    }
}

if (!function_exists('excel_export')) {
    function excel_export($headers,$data,$name,$format)
    {
        return \Excel::download(new Export($headers,$data), $name.'.'.$format);
    }
}

if(!function_exists('mobile_msg')){
    function mobile_msg($mobile,$text){
        $smsText= $text;
        $userMobileNo = $mobile;
        $user = "P2P";
        $pass = "r=20P196";
        $sid = "P2POTP";
        $url="http://sms.sslwireless.com/pushapi/dynamic/server.php";
        $param="user=$user&pass=$pass&sms[0][0]=$userMobileNo&sms[0][1]=".urlencode($smsText)."&sms[0][2]=123456789&sid=$sid";
        $crl = curl_init();
        curl_setopt($crl,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($crl,CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($crl,CURLOPT_URL,$url);
        curl_setopt($crl,CURLOPT_HEADER,0);
        curl_setopt($crl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($crl,CURLOPT_POST,1);
        curl_setopt($crl,CURLOPT_POSTFIELDS,$param);
        $response = curl_exec($crl);
        curl_close($crl);
    }
}

if (!function_exists('redis_error')) {
  function redis_error() {
      try {
          Redis::connect('127.0.0.1',3306);
          return false;
      } catch (\Exception $e) {
          return true;
      }
  }
}


if (!function_exists('get_redis')) {
  function get_redis($table){
      if(!redis_error()){
          return Redis::get($table);
      }
      return null;
  }
}


if (!function_exists('set_redis')) {
  function set_redis($table,$data){
      if(config('p2p.redis') && !redis_error()){
          Redis::set($table,$data);
      }
  }
}


if (!function_exists('redis_exists')) {
  function redis_exists($table){
      if(!redis_error()){
          if(Redis::get($table) != null){
              return true;
          }else{
              return false;
          }
      }else{
          return false;
      }
  }
}
