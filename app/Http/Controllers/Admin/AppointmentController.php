<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\CustomerFeedback;
use App\Model\AppointmentAdmin;
use Illuminate\Http\Request;
use App\Model\Appointment;
use App\Model\Admin;
use App\Model\User;

class AppointmentController extends Controller
{
  public function create(Request $request)
  {
     $user_id = auth_id();
     $appointment = new Appointment;
     $appointment->user_id = $user_id ? $user_id : '';
     $appointment->composition_id = $request->composition_id ? $request->composition_id : '';
     $appointment->appointment_type = isset($request->composition_id) ? '' : "general";
     $appointment->title = $request->title;
     $appointment->description = $request->description;
     $appointment->save();
     $msg = ['message' => 'Appointment created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function createByAdmin(Request $request)
  {

    $user_id = User::where('phone',$request->phone)->value('id');
    $client_id = null;
    if($user_id){
      $client_id = $user_id;
    }else{
      $user = new User;
      $user->first_name = $request->phone;
      $user->last_name = ".";
      $user->email = $request->phone;
      $user->phone = $request->phone;
      $user->password = bcrypt($request->phone);
      $user->save();
    }

      $appointment = new Appointment;
      $appointment->created_by = auth_admin()->id;
      $appointment->user_id = $client_id ? $client_id : $user->id;
      $appointment->title = $request->title;
      $appointment->description = $request->description;
      $appointment->appointment_type = $request->appointment_type ? $request->appointment_type : "";
      $appointment->save();
      $msg = ['message' => 'Appointment created', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
      if ($request->id) {
          $appointment = Appointment::with(['user','feedbacks'])->where('appointments.id',$request->id)->first();
          $msg = ['message' => 'Appointment retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$appointment),200);
      }

      $per_page = $request->per_page ?? 2;
      $appointments = Appointment::with(['user'])->paginate($per_page);

      $msg = ['message' => 'Appointment retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$appointments),200);
  }

  public function update(Request $request)
  {
     $customer_feedback = new CustomerFeedback();
     $customer_feedback->appointment_id = $request->id;
     $customer_feedback->customer_feedback = $request->customer_feedback;
     $customer_feedback->note = $request->note;
     $customer_feedback->assign_to = $request->assign_to;
     $customer_feedback->save();
     $msg = ['message' => 'Appointment updated', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $category = Appointment::where('id',$request->id)->delete();
    $msg = ['message' => 'Appointment Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }


  public function assignStuffLists(Request $request)
  {
    if($request->show_all){
      $assign_stuffs = Admin::where('is_super_admin','!=',1)->get();
      $msg = ['message' => 'Assign Employee retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$assign_stuffs),200);
    }

  }

  public function getAssignStuff(Request $request)
  {
    $admins = AppointmentAdmin::where('appointment_id',$request->appointment_id)->pluck('admin_id')->toArray();
    $assign_stuffs = Admin::whereIn('id',$admins)->get();
    $msg = ['message' => 'Assign Employee retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$assign_stuffs),200);

  }

  public function setAssignStuff(Request $request)
  {
    if(AppointmentAdmin::where('appointment_id',$request->appointment_id)->where('admin_id',$request->admin_id)->exists()){
      $msg = ['message' => 'This person has already assigned', 'status' => 'info', 'success' => false];
      return response()->json(output($msg,[]),200);
    }
    $assign = new AppointmentAdmin;
    $assign->appointment_id = $request->appointment_id;
    $assign->admin_id = $request->admin_id;
    $assign->save();

    $appointment = Appointment::where('id',$request->appointment_id)->first();
    $admin = Admin::where('id',$request->admin_id)->first();

    if($admin->mobile){
      $apMsg = "You have been assigned for a consultancy, appointment id is - $appointment->id . p2p";
      mobile_msg($admin->mobile,$apMsg);
    }

    if($admin->email){
      $apMsg = "You have been assigned for a consultancy, appointment id is - $appointment->id . p2p";
      send_mail($admin->email,'email.admin.appointment', $apMsg,['admin'=>$admin,'appointment'=>$appointment]);
    }

    $msg = ['message' => 'Assign Employee success', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
  }

  public function deleteAssignStuff(Request $request)
  {
    $delete = AppointmentAdmin::where('appointment_id',$request->appointment_id)
                ->where('admin_id',$request->admin_id)
                ->delete();
    $msg = ['message' => 'Assign Employee has been removed successfully', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
  }

  public function customerFeedback(Request $request)
  {
    $cf = new CustomerFeedback;
    $cf->appointment_id = $request->id;
    $cf->customer_feedback = $request->customer_feedback;
    $cf->note = $request->note;
    $cf->assign_to = auth_admin()->id;
    $cf->save();
    $msg = ['message' => 'Customer Feedback created', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
  }

  public function getCustomerFeedback(Request $request)
  {
    $getcf = CustomerFeedback::where('appointment_id',$request->id)->get();
    $msg = ['message' => 'Customer Feedback retrieved', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$getcf),200);
  }

  public function deleteCustomerFeedback(Request $request)
  {
    $delete = CustomerFeedback::where('appointment_id',$request->appointment_id)
                ->where('id',$request->id)
                ->delete();
    $msg = ['message' => 'Customer Feedback has been removed successfully', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
  }
}
