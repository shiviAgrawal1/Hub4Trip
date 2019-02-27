<?php
namespace App\Http\Controllers;

use App\Schedule;
use App\Trip;
use App\User;
use Illuminate\Http\Request;

class Trip_scheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['only' => [
             
        ]]);
    }
    public function search($id)
    {
      
    }

    public function create(Request $request)
    {
        $api_token = $request->api_token;
        $type = User::where('api_token', '=', $api_token)->where('type', '=', 'trip organiser')->get('email');
        //$user=User::where('api_token', '=', $api_token)->get('email');
        if ($type && Trip::where('trip_id', '=', $request->trip_id)->where('trip_organiser', '=', $type)->exists()) {
            Schedule::create($request->all());
            return response("created", 200);
        }
        return response("not allowed", 401);

    }

    public function modify($id, Request $request)
    {
        $api_token = $request->api_token;
        $type = User::where('api_token', '=', $api_token)->where('type', '=', 'trip organiser')->get('email');
        //$user=User::where('api_token', '=', $api_token)->get('email');
        if ($type && Trip::where('trip_id', '=', $id)->where('trip_organiser', '=', $type)->exists()) {
            Schedule::update($request->all());
            return response("updated", 200);
        }
        return response("not allowed", 401);

    }

    public function delete($id, Request $request)
    {
        $api_token = $request->api_token;
        $type = User::where('api_token', '=', $api_token)->where('type', '=', 'trip organiser')->get('email');
        //$user=User::where('api_token', '=', $api_token)->get('email');
        if ($type && Trip::where('trip_id', '=', $id)->where('trip_organiser', '=', $type)->exists()) {
            Schedule::delete();
            return response("deleted", 200);
        }
        return response("not allowed", 401);
    }

}
