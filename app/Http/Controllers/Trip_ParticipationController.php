<?php
namespace App\Http\Controllers;

use App\Tripparticipation;
use App\User;
use Illuminate\Http\Request;

class Trip_participationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'search',
            'create',
            'delete',
        ]]);
    }
    public function search($id)
    {
        $people = Trip_participation::where('trip_id','=',$id)->get();
        if ($people) {
            return response()->json($people);
        }

        return response("no such trip exixts");
    }
    public function create(Request $request)
    {$api_token = $request->api_token;
        $type = User::where('api_token', '=', $api_token)->get('type');
        if ($type == "user") {
            Trip_participation::create($request->all());
            return response("trip participation");
        }
        return response("NOT ALLOWED",401);
    }
    public function delete($id, Request $request)
    {
        $api_token = $request->api_token;
        if (Trip_participation::where('trip_id','=',$id)) {
            $type = User::where('api_token', '=', $api_token)->get('type');
            if ($type == "trip organiser") {
                if ($trip_organiser = User::where('api_token', '=', $api_token)->get('email')) {
                    if ($trip_organiser == Create_trip::where('trip_id', '=', $id)->get('trip_org_email')) {
                        $trip = Tripparticipation::where('trip_id', '=', $id)->delete();
                        return response("TRIP DELETED ", 200);
                    }
                    return response("MISMATCH", 401);
                }
            } else if ($type == "user") {
                $user = User::where('api_token', '=', $api_token)->get('email');
                if ($user = Tripparticipation::where('trip_id', '=', $id)->get('user')) {
                    $trip = Tripparticipation::where('trip_id', '=', $id)->delete();
                    return response("TRIP DELETED ", 200);
                }
                return response("WRONG USER",401);

            }
            return response("YOU DON'T HAVE PROPER PERMISSION", 401);
        }
        return response("NOT FOUND",404);
    }

}
