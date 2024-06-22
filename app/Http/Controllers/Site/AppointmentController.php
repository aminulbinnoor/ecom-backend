<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Appointment;

class AppointmentController extends Controller
{
    public function createAppointment(Request $request)
    {
      $user_id = isset(auth_user()->id) ? auth_user()->id : '';
      $appointment = new Appointment;
      $appointment->user_id = $user_id;
      $appointment->composition_id = $request->composition_id ? $request->composition_id : '';
      $appointment->name = $request->name;
      $appointment->email = $request->email;
      $appointment->phone = $request->phone;
      $appointment->message = $request->message;
      $appointment->appointment_type = isset($request->composition_id) ? 'looks' : "general";
      $appointment->status = "initial";
      $appointment->save();

      if(auth_user()->email){
          send_mail(auth_user()->email,'email.user.appointment', "Your Appointment has created. Appointment id - $appointment->id",['appointment' => $appointment]);
      }
      if(auth_user()->phone) {
          mobile_msg(auth_user()->phone,"Your Appointment has created. Appointment id - $appointment->id .contact number  +8801932360360. P2P");
      }

      $msg = ['message' => 'Appointment created successfully', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
    }

    public function get(Request $request)
    {
      //$user_id = auth_user()->id;
      $appointment = Appointment::where('user_id',1)->get();
      $msg = ['message' => 'Appointment retrieved successfully', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$appointment),200);
    }
}
