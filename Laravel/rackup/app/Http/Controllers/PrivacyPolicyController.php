<?php

namespace App\Http\Controllers;

use App\ImageStudent;
use App\PdfCover;
use App\Student;
use Illuminate\Http\Request;
use App\UserDetails;
use App\ContentType;
use App\Category;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;
use Mockery\Matcher\Type;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class PrivacyPolicyController extends Controller
{
    public function showPrivacyPolicy(){
        return view('privacyPolicy');
    }
}
