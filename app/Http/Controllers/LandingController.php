<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    
    public function index(Request $request){
        $cookie_name = "is_age_verified";
        if(!isset($_COOKIE[$cookie_name])) {
            return view('home-page');
        } else {
            return view('main-page');
        }
    }

    public function mainPage(Request $request){
        $cookie_name = "is_age_verified";
        if(!isset($_COOKIE[$cookie_name])) {
            $cookie_value = "yes";
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        return view('main-page');
    }
    
    public function privacyPolicy(Request $request){
        return view('privacy-policy-page');
    }

    public function termCondition(Request $request){
        return view('term-condition-page');
    }

    public function aboutUs(Request $request){
        return view('about-us-page');
    }

    public function contactUs(Request $request){
        return view('contact-us-page');
    }
}
