<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use MaddHatter\LaravelFullcalendar\Calendar;
use Mockery\CountValidator\Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use App\UserDetails;
use App\TeacherAppointmentSlots;
use App\AppointmentRequest;
use App\CalendarEvent;
use Tymon\JWTAuth\Providers\Storage\StorageInterface;
use Carbon\Carbon;

class NotificationController extends Controller
{
    //
    public function  getNotifications(Request $request){


        try {
            $token = $request->get('token');;
            $user = JWTAuth::toUser($token);
            $user_Id = $user->id;

        } catch (TokenExpiredException $e) {
            return Response::json(['Token expired'], 498);
        } catch (TokenInvalidException $e) {
            return Response::json(['Token invalid']);
        }
//        $user_Id = 2;
        $notifictions = DB::table('notifications')
            ->where('parent_id', $user_Id)->get();

        return Response::json([$notifictions, HttpResponse::HTTP_OK]);
    }
}
