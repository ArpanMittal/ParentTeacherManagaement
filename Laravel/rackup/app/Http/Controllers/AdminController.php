<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\UserDetails;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //Show edit profile page for logged in teacher user
    public function editProfileDetails (Request $request){

        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        
        $username = $user->username;
        $userDetails = UserDetails::where('user_id',$id)->first();
        $name = $userDetails->name;
        $gender = $userDetails->gender;
        $contact = $userDetails->contact;
        $address = $userDetails->address;
        
        $profile_details=array(
            'id'=>$id,
            'name'=>$name,
            'gender'=>$gender,
            'contact'=>$contact,
            'address'=>$address,
            'username'=>$username
        );
        return view('admin.editProfile', compact('profile_details'),$data);
    }
    //Update edited profile details by logged in teacher user
    public function updateProfileDetails ($id,Request $request){

        $rules = array(
            'contact'=>'digits:10'
        );
        $this->validate($request,$rules);
        $address= $request->input("address");
        $contact = $request->input("contact");
        try{
            \DB::beginTransaction();
            DB::table('userDetails')
                ->where('user_id', $id)
                ->update([
                    'address' => $address,
                    'contact' => $contact
                ]);

        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('editProfileDetails'))->with('failure', 'Cannot update Profile Details');
        }
        \DB::commit();

        return redirect(route('editProfileDetails'))->with('success', 'Profile Details Updated Successfully.');
    }
}