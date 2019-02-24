<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     
    public function __construct() {
        // $this->middleware('auth', ['only' => [
        //     'showOneUser',
        //     'showAllUsers',
        //     'update',
        //     'delete'
        // ]]);
    }

    public function showAllUsers()                                                 //also needs restriction of admin
    {
        // $result = User::select('select * from users');
        // return response()->json($result);
         
        return response()->json(User::all());
    }

    public function showOneUser($id)                                              //show profile of user
    {
        return response()->json(User::find($id));
    }
    


   //Register
    public function createUser(Request $request)
    {
        $request['api_token']=str_random(6);
        $request['password']=md5($request['password']);
        $user = User::create($request->all());
        return response()->json($user,201); 
    }

    //login
    public function login(Request $request)
    {
       $user = User::where('email',$request['email'])->first();
       if($user->password==md5($request->password))
       {
         return response("yes");
       }
       else
          return response("no");

    }

       
                                                                                     //update ur own profile or update by admin (but need restriction such that no one will be able to modify email as it is primary key and all depends on it, else need to care of redundancy)
                                                                                                                                          
    public function updateUser(Request $request , $id)                                
    {     
          $api_token=$request->api_token;
          $type=User::where('api_token','=',$api_token)->value('type');
          if($type=='admin')
          { 
          $user = User::findOrFail($id);
          $user->update($request->all());
          return response()->json($user,200);
          }
          return response()->json("YOU DON'T HAVE A PROPER PERMISSION",401 );
    }

                                                                                      //delete ur own account or deletion by admin (take email as primary key, else need to handle redundancy)
    public function deleteUser(Request $request , $id)       
    {    
         $api_token=$request->api_token;
         $type=User::where('api_token','=',$api_token)->value('type');
         if($type=='admin')
         {
         User::findOrFail($id)->delete();
         return response('Deleted Successfully',200);
         }
         return response()->json("YOU DON'T HAVE A PROPER PERMISSION",401);
    }



}
