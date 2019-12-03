<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\All_pet;

class All_petsController extends Controller
{
    public function index(){
	 
    	$all_pets = All_pet::all();
 
        return view('petdetails.index',compact('all_pets'));
    }
 
    public function create(){

        return view('petdetails.create');

    }
 
    public function pet_details(Request $request){

    	$request->validate([
            'profile_pic' => 'required|file|max:1024',
        ]);

        $img_name = "pet_img".time().'.'.request()->profile_pic->getClientOriginalExtension();
		$request->profile_pic->storeAs('logos', $img_name);

        /* return back()
            ->with('success','You have successfully upload image.'); */
 
        $all_pet = new All_pet;
 
        $all_pet->name = request('y_name');
        $all_pet->dob = request('date_of_birth');
        $all_pet->type_of_pet = request('type_of_pet');
        $all_pet->address = request('address');
        $all_pet->phone_no = request('ph');
        $all_pet->gender = request('pet_gender');
        $all_pet->human_name = request('human_name');
        $all_pet->user_email = request('user_email');
        $all_pet->relationship_with_human = request('relationship_with_human');
        $password = Input::post('user_password');
		$hashed = Hash::make($password);
        /*$all_pet->user_password = request('user_password');*/
        $all_pet->user_password = $hashed;
        $all_pet->profile_picture = $img_name;
 
        $all_pet->save();
 
        return redirect('/pet'); 
    }

    public function szdfsfsfsf(Request $request){

        $user_email = request('user_email_id');
        /*echo $user_email."</br>";*/
        $password = request('user_password_with');

        $all_pet = new All_pet;   // call model

        $users = $all_pet::where('user_email', $user_email)->first(); // query via model Query ORM Eloquent
        
        /*echo $password."</br>";
		$hashed_password = Hash::make($password);*/
        /*echo $hashed_password."</br>";*/
		//$users = DB::table('all_pets')->where('user_email', $user_email)->first(); // database query builder
        /*$user = DB::table('all_pets')->find(4);*/
        /*$user = DB::table('all_pets')->pulak();*/
        /*$user = DB::table('all_pets')->first();*/
        /*$user = DB::table('all_pets')->where('id', 3)->first();*/
        //Hash::check($password, $users->user_password);
        if (!Hash::check($password, $users->user_password)) {
	       //echo "not success";
	        return redirect('/'); 
	    }else{
	    	//echo "success";
            Session::put('user_name', $users->name);
            Session::put('user_email', $users->user_email);
            //echo $value = Session::get('user_name');
            //session(['key' => 'value']);
            //Session::set('user_name', $users->name);
	    	return redirect('/profile'); 
	    }	 
        
    }

    public function user_profile(){

        //$all_pets = All_pet::all();
        $all_pet = new All_pet;   // call model

        $users = $all_pet::where('user_email', Session::get('user_email'))->first();
        $data = array(
            'image'      => $users->profile_picture, 
            'gender'     => $users->gender, 
            'address'    => $users->address, 
            'type_of_pet'=> $users->type_of_pet
        );
        //return View::make("user/regprofile")->with($data);
        return view('petdetails.user_profile')->with( $data );
    	//echo "Pet Profile Page";
    }
}
