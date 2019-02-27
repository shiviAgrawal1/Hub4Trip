<?php

namespace App\Http\Controllers;
use App\Create_trip;
use App\User;
use App\Trip_participation;
use App\Trip_schedule;
use App\Checkpoint;
use DB;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;

class TripController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
  
  //we have to design a interface such that only trip organiser can see create , update and delete forms.
    public function createTrip(Request $request)
    {
        $api_token=$request->api_token;
        if(User::where('api_token','=',$api_token)->where('type','=','trip organiser')->exists())
        {
         //     Trip::create($request->all());
         //    Checkpoints::create([
         //        'trip_id' => $request->trip_id,
         //        'checkpoint_no' => 'source',
         //        'checkpoint' => $request->source,
         //    ]);
         //    Checkpoints::create([
         //        'trip_id' => $request->trip_id,
         //        'checkpoint_no' => 'destination',
         //        'checkpoint' => $request->destination,
         //    ]);
         //    return response($request, 200);
        
                    $trip = Create_trip::create($request->all());
                    return response()->json($trip,200);
          }
          return response("YOU DON'T HAVE PROPER PERMISSION",401);
    }




                                                            //if delete the trip then information regarding trip must have to delete.
    public function delete($id,Request $request)
   {
    $api_token=$request->api_token;
    if($trip_organiser=User::where('api_token','=',$api_token)->where('type','=','trip organiser')->get('email'))
    { 
        if($trip_organiser==Trip::where('trip_id','=',$id)->get('trip_org_email')){
        $trip=Trip::where('trip_id','=',$id)->delete();
        return response("TRIP DELETED ",200);
    }
    return response("MISMATCH",401);
    }
    return response("YOU DON'T HAVE PROPER PERMISSION",401);
   }




    public function update($id,Request $request)
   {
    $api_token=$request->api_token;
    if($trip_organiser=User::where('api_token','=',$api_token)->where('type','=','trip organiser')->get('email'))
    { 
        if($trip_organiser==Trip::where('trip_id','=',$id)->get('trip_org_email')){
        $trip=Trip::where('trip_id','=',$id)->update($request->all());
        return response("TRIP UPDATED ",200);
    }
    return response("MISMATCH",401);
    }
    return response("YOU DON'T HAVE PROPER PERMISSION",401);
    }




    public function search($source,$destination)
    {
    $trip= Create_trip::where('source',$source)->where('destination',$destination)->get();
    //then show list of company , team_organiser_name ,rating_of_compny_or_trip_org , max_accomodation, current_accomodation, see package
    //if status==running||end or max_accomodation == current_accomodation then show it blurr
     return response()->json($trip);

    }
    
    //by destination only
    public function search_by_destination($destination)
    {
    $trip= Create_trip::where('destination',$destination)->get();
    //then show list of company , team_organiser_name ,rating_of_company or trip_org , max_accomodation, current_accomodation, see package
    //if status==running||end or max_accomodation == current_accomodation then show it blurr
     return response()->json($trip);

    }




    //create package by trip organiser
    public function create_package(Request $request)
    {

    }

    //see package
    public function see_package()
    {

    }

    //if user wants to join and press JOIN THIS TRIP button then request will send to trip_organiser then trip_org add that user into trip  and notify him the same.
    public function add_user_to_trip(Request $request)     //or fill Trip_participation table.
    {
        $api_token=$request->api_token;
        if(User::where('api_token','=',$api_token)->where('type','=','trip organiser')->exists())
         {
        
                    $trip = Trip_participation::create($request->all());
                    return response()->json($trip,200);
          }
          return response("WAIT FOR TRIP_ORG RESPONSE",401);
    }





    //update and delete entry of Trip_participation.
    public function update_user_from_trip()
    {

    }


    public function delete_user_from_trip()
    {

    }


    //once user joins the trip he/she will be able to see info of other group members and trip_org and also able to contact them.
    public function user_after_joining_trip()
    {

    }

    //show user's visited trips
    public function user_visited_trip($email)                                                 //**also for trip_org**//
    {
        $user_trip_id= Trip_participation::where('user_email',$email)->where('trip_status','=','end')->get('trip_id');
        //from here we get  trip_id but it is in form of array and we have to find details of all these trip id and send them in response//
        $trip=array();
        $i=1;
        foreach ($user_trip_id as $key => $user) 
        {  
           //$trip[$i] = array();
           $trip[$i]= Create_trip::where('trip_id',$user->trip_id)->get();
           $i++;   
        }
        return response()->json($trip);

          //trip_id/name , trip_org ,  destination , time, rating.
          
    }//then show detail of trip on clicking trip_id using show_trip_detail


    //show user's running trips
    public function user_current_trip($email)                                                 //**also for trip_org**//
    {
        $user_trip_id= Trip_participation::where('user_email',$email)->where('trip_status','=','running')->get('trip_id')->distinct();
                                                                              //trip_status== 'start' // 
        $trip=array();
        $i=1;
        foreach ($user_trip_id as $key => $user) 
        {  
           //$trip[$i] = array();
           $trip[$i]= Create_trip::where('trip_id',$user->trip_id)->get();
           $i++;   
        }
        return response()->json($trip);

    }


    //**********************TRIP ORGANISER*************************//


    //show trip details by using trip_id
    public function show_trip_detail($trip_id)
    {
          $trip = Create_trip::where('trip_id', $trip_id)->get();
          $trip_schedule = Trip_schedule::where('trip_id', $trip_id)->first();
          $user_email= Trip_participation::where('trip_id',$trip_id)->get('email')->distinct();
            $user = array();
            $i=1;
            foreach ($user_email as $key => $email)
            {
                 $user[$i] = User::where('email',$user_email)->first();
                 $i++;
            }
          $trip_activity = Checkpoint::where('trip_id',$trip_id)->get();
          return response()->json($trip , $trip_schedule, $user_email, $trip_activity);

    }

    //show members of trips using trip_id
    public function show_members($trip_id)
    {
        $user_email= Trip_participation::where('trip_id',$trip_id)->get('email')->distinct();
        $user = array();
        $i=1;
        foreach ($user_email as $key => $email)
        {
             $user[$i] = User::where('email',$user_email)->first();
             $i++;
        }
        return response()->json($user);
        
               
    }//then on clicking on member's name their detail will show. 

    //show detail of members using user_email by showOneUser@UserController or by this function
    public function members_detail($email)
    {
        $user = User::where('email', $email)->first();
        return response()->json($user);
    }


//**************************ADMIN**********************************//

//admin can change the type of user
    public function updateUser(Request $request , $id)                                
    {     
          $api_token=$request->api_token;
          $type=User::where('api_token','=',$api_token)->get('type');
          if($type=='admin')
          { 
          $user = User::findOrFail($id);
          $user->update($request->all());
          return response()->json($user,200);
          }
          return response()->json("YOU DON'T HAVE A PROPER PERMISSION",401 );
    }

//search by character like django
    public function search_by_character()
    {
        
    }

//show all trips company_wise
    public function search_by_company()
    {

    }

//show all trips trip_organiser_wise
    public function search_by_trip_org()
    {
        
    }

//show all trips time_wise
    public function search_by_time()
    {
        
    }

//show all trips user_wise
    public function search_by_user($email)
    {
        $user_trip_id= Trip_participation::where('user_email',$email)->get('trip_id')->distinct();
                                                                              //trip_status== 'start' // 
        $trip=array();
        $i=1;
        foreach ($user_trip_id as $key => $user) 
        {  
           $trip[$i] = array();
           $trip[$i]= Create_trip::where('trip_id',$user->trip_id)->get();
           $i++;   
        }
        return response()->json($trip);

    }

//show trip_org detail company_wise
    public function show_triporg_by_company()
    {
         
    }

// deletion and updation of trip_org by using user updation and deletion and show trip_org by using showuser.

//show trips source_wise
    public function search_by_source($source)
    {
     $trip= Create_trip::where('source',$source)->get();
    //then show list of company , team_organiser_name ,rating_of_company or trip_org , max_accomodation, current_accomodation, see package
    //if status==running||end or max_accomodation == current_accomodation then show it blurr
     return response()->json($trip);
    }

//show trips location and time wise both 


//show trips destination_wise check above "search_by_destination" function




//**************************SEARCH*********************************//




}
