<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Firebase\Auth\Token\Exception\InvalidToken;
//use Kreait\Firebase\Auth;
use App\Models\User;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Brands;
use App\Models\Distillerys;
use App\Models\Carts;
use App\Models\UserAddresses;
use App\Models\ProductFavourite;
use App\Models\DeviceRegister;
use App\Models\Notification;
use App\Models\Lifestyles;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Crypt;
use Hash;
use Validator;
use File;
use Mail;
use DB;

class UserController extends BaseController
{
    public $successStatus = 200;
    public function __constructor(){
        #$this->middleware('auth:api');
    }

    public function login(Request $request){
        $input = $request->json()->all();

        /* $serviceAccount = [
            "type" => "service_account",
            "project_id" => "stillmans-bbf92",
            "private_key_id" => "46dba77c93ffa3b67cbd7ba10a3593772811c438",
            "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCm/Z3rUIqMrRvQ\nd0JZZkQvfvnm2rGyPoEuIDXSyx8eLi/GfQ87I3DAzLX8QGrIaL+b2zSpwwB/MbFu\ngrVgvv4X3tFnjpyGsXlMKNNDwfqpGocdFcdzgr2UIJtUQIkiEO6Wmybsv1Tb8vh8\nAi6UCvm1YSb1QJaAeWA2iGRAowDkFY+a/NyEFHpfUH3BACBjyRm3GG4Y1fzmYxdc\nqoEd7zEGrDQ0CrFZMYeBsaLeda8+9t429rcxJrxuzYaxFl0MXpRBysm7y0MOrlxD\n3vcK9TQELb1dGL5vuPVqp6UQw+qkyg2tOQ9ZvdkkrALIwrEt4WVYY1MNWq1F6wqw\nfjfXHRlFAgMBAAECggEAIW2nMX49wgP8S6q4BDvx3d0qCxHP3Rngk7p8c85YFBtU\nUoRh6mmBjoLcUlsV1TkM1FcZD3u7C3tH5NKzGuMY6+/dlQCqKld4K+wHQKrh8fye\nvA6KTwAG0dKGLaM+oGxpyonUDLQPeXlFz1NDfjzFOI8ydImBp0XE4h3DDeJjkQTC\nEiBYFjP54ivCZqb8zBH6GPP3GqtOaAxEY0lumb5fsCo622uVfacF3Bbkx3xbMeiD\nkhSGvKogRCuWP19ups/Z5hy7qz4/D5gtg6xkireWzR4gn1tgGIFc+QuOo+esqFoC\nP59B9neOCFBTuisrUnET3BZIZH15mrzWehNawbrUYQKBgQDXOQpSdB4HilwinCxZ\nBAh7SSrl6u8V4s+sp4/t48CbXufy6q3dMsfMXQ9J4WG4EXbBtkDKP79EXrPrt/xR\nvBby5dgZQylkNa3UXoL+jU9tZcK9M3M5/R7ACQyPPTq+6fbZG1IRh4VpvE0hczi4\nxHycNj1CMtU/WMDSXR8+ruKS4QKBgQDGoSwVtBzAYneWJiHHhO3/qSUIFOC9Vh0f\nnoGLTmrzSgk5cbUg0DA0nvuJJ5hIIh/+es5UnQ0BsvnBoTSU8irEGTR81GeyBNlE\nl7F2yx9jJ8sGnLTyzXoG0IYUl8PqF2SWWnwA06PDZoKFPbwXKZoNFxT1O2hKD8rb\n8kupyjx25QKBgBVm2azrWRUc4B6c8xgC+6ju1LI6U34UqNwdaYWZcUzB+kTAq3tk\nklOzKVMKc4IvUmTe78Bs6GMvG6skB5ZFIu8ShhO1BwetAtUsdNBFUxGo3d2coSey\nEP29qJ8R0qarc12Rhu6xUEX8cJsT7x8Igu+xwRO1JFmBRONmWTYNhABhAoGAUoms\nyQQEIomISfNkfAUn5OIDil0qmmLLTVVPXXTjil9Mf/tZGd6I+YTK3059wBO2Gcgs\nLwrn2bnmgOic8fvDe6NBpVGaGWZcZl1mmAeF7m0dt4yswGxT9br2zF3YQlEgMj49\nYxaO6WaSfA2QOEvIQQJDajgaeRQg5s7DVDW+dGUCgYAaG6vsWXH2XZIMXoM6elyN\n29c9a8VVLe4nID7gxfw0eWf6ygxDN74POWbu00dUDlOHzcgpW0QwT+2jgCOHdE8B\nwTn8NU3SBuWNXMKpvadykq8OqWAfWA5JWQ29DdDV3QoqbhTDdXkCE5qfoaXsq8aE\ntvHxNKtZX0Le4YzhhTnWUw==\n-----END PRIVATE KEY-----\n",
            "client_email" => "firebase-adminsdk-grijy@stillmans-bbf92.iam.gserviceaccount.com",
            "client_id" => "112686870661816878646",
            "auth_uri" =>  "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-grijy%40stillmans-bbf92.iam.gserviceaccount.com"
        ]; */

        /* $serviceAccount = [
            "type" => "service_account",
            "project_id" => config('services.firebase.project_id'),
            "private_key_id" => config('services.firebase.private_key_id'),
            "private_key" => config('services.firebase.private_key'),
            "client_email" => config('services.firebase.client_email'),
            "client_id" => config('services.firebase.client_id'),
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url" => config('services.firebase.client_x509_cert_url')
        ]; */

       // echo "<pre>";print_r($factory);die;

        /* $factory = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri(config('app.FIREBASE_URL'));

        $auth = $factory->createAuth(); */

        /* $signInResult = $auth->signIn();
        $refreshToken = $signInResult->refreshToken();
        echo $refreshToken;die; */
        //$idTokenString = $request->input('Firebasetoken');

        /* $idTokenString = $request->header('refreshedToken');

        try { // Try to verify the Firebase credential token with Google

            $verifiedIdToken = $auth->verifyIdToken($idTokenString);

          } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage()
            ], 401);

          } catch (InvalidToken $e) { // If the token is invalid (expired ...)

            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage()
            ], 401);

          }

        echo "<pre>";print_r($factory);die; */


        ///$auth = app('firebase.auth');
        //echo "<pre>11";print_r($auth);die;
        // Retrieve the Firebase credential's token
        //$idTokenString = $request->input('Firebasetoken');

        if(Auth::attempt(
                ['email' => base64_decode($input['email']),
                'password' => base64_decode($input['password'])
                ]
            )){
            $user = Auth::user();
            $user_id = $user->id;
            $api_token = Str::random(60);

            User::where('id', $user_id)->update(['api_token' => $api_token]);
            //auth()->user()->setNewApiToken();

            /* $factory = (new Factory())->withDatabaseUri(config('app.FIREBASE_URL'));
            $db = $factory->createDatabase();
            $db->getReference('users/' . $user_id)
                ->set([
                    'token' => $api_token,
                    'timestamp' => Carbon::now()->timestamp
                ]); */

            $success['token'] = $api_token;
            $success['name'] = $user->name;
            $success['email'] = $user->email;
            $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('d/m/Y');
            $success['phone'] = $user->phone;
            $success['login_type'] = $user->phone;
            return $this->sendResponse($success, 'User login successfully.');
        } else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function register(Request $request)
    {
        $authorization = $request->header('Authorization');
        //echo $authorization;die;
        $input = $request->json()->all();
        //echo "<pre>";print_r($data);die;
        //$input = $request->all();
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'dob' => 'required|date_format:d/m/Y',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 401);
        }

        if (User::where('email', base64_decode($input['email']))->exists()) {
            return $this->sendError('exists.', ['error'=>'email already exists']);
        }else{
            $api_token = Str::random(60);
            $input['name'] = $input['name'];
            $input['email'] = base64_decode($input['email']);
            $input['password'] = bcrypt(base64_decode($input['password']));
            $input['phone'] = base64_decode($input['phone']);
            $input['dob'] = Carbon::createFromFormat('d/m/Y', $input['dob'])->format('Y-m-d');
            $input['app_version'] = $input['appVersion'];
            $input['login_type'] = $input['loginType'];
            $input['profile_pic'] = $input['profilePic'];
            $input['fb_provider_id'] = $input['fbProviderId'];
            $input['device_id'] = $input['deviceId'];
            $input['is_email_verified'] = $input['isEmailVerified'];
            $input['device_model'] = $input['deviceModel'];
            $input['fb_uid'] = $input['fbUid'];
            $input['api_token'] = $api_token;
            $user = User::create($input);



            //$success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['api_token'] =  $api_token;
            return $this->sendResponse($success, 'User register successfully.');
        }
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        $user = Auth::user();
        $success['name'] = $user->name;
        $success['email'] = $user->email;
        $success['dob'] = $user->dob;
        $success['phone'] = $user->phone;
        return $this->sendResponse($success, 'Get user information successfully.');
    }

    /**
     * details1 api
     *
     * @return \Illuminate\Http\Response
     */
    public function details1(Request $request)
    {
        $token = $request->header('api_token');
        echo 'hi'.$token;die;
        $user_id = $this->check_auth($token);
        if($user_id){
            //$user = Auth::user();
            //return response()->json(['success' => $user], $this-> successStatus);
            $user = $user = User::where('id',$user_id)->first();

            $success['name'] = $user->name;
            $success['email'] = $user->email;
            $success['dob'] = $user->dob;
            $success['phone'] = $user->phone;
            return $this->sendResponse($success, 'Get user information successfully.');
        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function check_auth($token)
    {
        if($token){
            $user = $user = User::where('api_token',$token)->first();
            if($user){
                return $user->id;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function signup(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [
            'loginType' => [
                'required',
                Rule::in(['google', 'fb', 'apple']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid loginType field');
        }

        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (!isset($input['authentication']) && empty($input['authentication'])) {
            return $this->sendError('The authentication field is required.');
        }

        if ($input['loginType']=="email" && !isset($input['password']) && empty($input['password'])) {
            return $this->sendError('The password field is required.');
        }

        if ($input['authentication'] == "0" && $input['loginType']=="email" && !isset($input['name']) && empty($input['name'])) {
            return $this->sendError('The name field is required.');
        }

        if ($input['authentication'] == "0"){
            if (User::where('email', base64_decode($input['email']))->exists()) {
                return $this->sendError('email already exists');
            }
        }

        if ($input['authentication'] == "1"){
            if (!User::where('email', base64_decode($input['email']))->exists()) {
                return $this->sendError('Email is not registered!');
            }
        }

        if (User::where('email', base64_decode($input['email']))->exists()) {
            $user = User::where('email', base64_decode($input['email']))->first();
            if($input['loginType']=="email"){
                if(isset($input['password'])){
                    if(Auth::attempt(
                        ['email' => base64_decode($input['email']),
                        'password' => base64_decode($input['password'])
                        ]
                    )){
                        $user = Auth::user();
                        $user_id = $user->id;
                        $api_token = Str::random(60);

                        $os_type = isset($input['os']) ? $input['os'] : $user->os;
                        $device_id = isset($input['deviceId']) ? $input['deviceId'] : $user->device_id;

                        User::where('id', $user_id)->update(['api_token' => $api_token,'login_type'=>$input['loginType'],
                        'device_id'=>$device_id,'os'=>$os_type]);

                        $success['token'] = $api_token;
                        $success['name'] = $user->name;
                        $success['email'] = $user->email;
                        $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('d/m/Y');
                        $success['dial_code'] = $user->dial_code;
                        $success['phone'] = $user->phone;
                        //$success['authentication'] = "1";
                        $success['login_type'] = $input['loginType'];
                        $success['profile_pic'] = $user->profile_pic;
                        return $this->sendResponse($success, 'User login successfully.');
                    } else{
                        return $this->sendError('Incorrect password.');
                    }
                }else{
                    return $this->sendError('please enter your password.');
                }
            }else{
                //if(User::where(['email'=> base64_decode($input['email']),'login_type'=>$input['loginType']])->exists()){
                    $user_id = $user->id;
                    $api_token = Str::random(60);

                    $os_type = isset($input['os']) ? $input['os'] : $user->os;
                    $device_id = isset($input['deviceId']) ? $input['deviceId'] : $user->device_id;

                    User::where('id', $user_id)->update(['api_token' => $api_token,'login_type'=>$input['loginType'],
                    'device_id'=>$device_id,'os'=>$os_type]);

                    $success['token'] = $api_token;
                    $success['name'] = $user->name;
                    $success['email'] = $user->email;
                    $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('m/d/Y');
                    $success['dial_code'] = $user->dial_code;
                    $success['phone'] = $user->phone;
                    $success['login_type'] = $input['loginType'];
                    //$success['authentication'] = "1";
                    $success['profile_pic'] = $user->profile_pic;
                    return $this->sendResponse($success, 'User login successfully.');
                /* }else{
                    return $this->sendError('email is register with another login account');
                } */

            }
        }else{
            $api_token = Str::random(60);

            if ($input['loginType']=="email" && !isset($input['password'])) {
                return $this->sendError('Validation Error.', ['password'=>['The password field is required.']], 401);
            }

            $input['name'] = isset($input['name']) ? $input['name'] : '';
            $input['email'] = base64_decode($input['email']);
            $input['password'] = isset($input['password']) ? bcrypt(base64_decode($input['password'])) : '';
            $input['phone'] = isset($input['phone']) ? base64_decode($input['phone']) : NULL;
            $input['dob'] = isset($input['dob']) ? Carbon::createFromFormat('d/m/Y', $input['dob'])->format('Y-m-d') : NULL;
            $input['app_version'] = isset($input['appVersion']) ? $input['appVersion'] : NULL;
            $input['login_type'] = isset($input['loginType']) ? $input['loginType'] : NULL;
            $input['profile_pic'] = isset($input['profilePic']) ? $input['profilePic'] : NULL;
            $input['fb_provider_id'] = isset($input['providerId']) ? $input['providerId'] : NULL;
            $input['device_id'] = isset($input['deviceId']) ? $input['deviceId'] : NULL;
            $input['is_email_verified'] = isset($input['isEmailVerified']) ? $input['isEmailVerified'] : NULL;
            $input['device_model'] = isset($input['deviceModel']) ? $input['deviceModel'] : NULL;
            $input['fb_uid'] = isset($input['uid']) ? $input['uid'] : NULL;
            $input['os'] = isset($input['os']) ? $input['os'] : NULL;
            $input['dial_code'] = isset($input['dialCode']) ? base64_decode($input['dialCode']) : NULL;
            $input['api_token'] = $api_token;
            $user = User::create($input);
            if($user){
                $success['token'] =  $api_token;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('d/m/Y');
                $success['dial_code'] = $user->dial_code;
                $success['phone'] = $user->phone;
                $success['login_type'] = $user->login_type;
                //$success['authentication'] = "0";
                $success['profile_pic'] = $user->profile_pic;
                return $this->sendResponse($success, 'User register successfully.');
            }else{

            }
        }
    }

    public function emailsignup(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [
            'loginType' => [
                'required',
                Rule::in(['email']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid loginType field');
        }

        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (!isset($input['authentication']) && empty($input['authentication'])) {
            return $this->sendError('The authentication field is required.');
        }

        if ($input['loginType']=="email" && !isset($input['password']) && empty($input['password'])) {
            return $this->sendError('The password field is required.');
        }

        if ($input['authentication'] == "0" && $input['loginType']=="email" && !isset($input['name']) && empty($input['name'])) {
            return $this->sendError('The name field is required.');
        }

        if ($input['authentication'] == "0"){
            if (User::where('email', base64_decode($input['email']))->exists()) {
                return $this->sendError('email already exists');
            }
        }

        if ($input['authentication'] == "1"){
            if (!User::where('email', base64_decode($input['email']))->exists()) {
                return $this->sendError('Email is not registered!');
            }
        }

        if (User::where('email', base64_decode($input['email']))->exists()) {
            $user = User::where('email', base64_decode($input['email']))->first();
            if($input['loginType']=="email"){
                if(isset($input['password'])){
                    if(Auth::attempt(
                        ['email' => base64_decode($input['email']),
                        'password' => base64_decode($input['password'])
                        ]
                    )){
                        $user = Auth::user();
                        $user_id = $user->id;
                        $api_token = Str::random(60);

                        if($user->is_email_verified == "false"){
                            //$code = random_int(1000, 9999);
                            //User::where('id', $user->id)->update(['reset_password_code' => $code]);

                            /* $message = 'For email verified  use this otp: '.$code;
                            $details = [
                                'title' => 'Mail from stillmanapp.com',
                                'body' => $message
                            ];

                            Mail::to(base64_decode($input['email']))->send(new \App\Mail\MyTestMail($details)); */


                            $response = [
                                'success' => "2",
                                'message' => "email is not verified.",
                                //"data" => ["code" => (string)$code]
                            ];
                            return response()->json($response, 200);

                            //return $this->sendError($success ,'email is not verified.');
                        }

                        $os_type = isset($input['os']) ? $input['os'] : $user->os;
                        $device_id = isset($input['deviceId']) ? $input['deviceId'] : $user->device_id;

                        User::where('id', $user_id)->update(['api_token' => $api_token,'login_type'=>$input['loginType'],
                        'device_id'=>$device_id,'os'=>$os_type]);

                        $success['token'] = $api_token;
                        $success['name'] = $user->name;
                        $success['email'] = $user->email;
                        $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('m/d/Y');
                        $success['dial_code'] = $user->dial_code;
                        $success['phone'] = $user->phone;
                        //$success['authentication'] = "1";
                        $success['login_type'] = $input['loginType'];
                        $success['profile_pic'] = $user->profile_pic;
                        return $this->sendResponse($success, 'User login successfully.');
                    } else{
                        /* $code = random_int(1000, 9999);
                        User::where('id', $user->id)->update(['reset_password_code' => $code]);

                        $message = 'For email verified  use this otp: '.$code;
                        $details = [
                            'title' => 'Mail from stillmanapp.com',
                            'body' => $message
                        ];

                        Mail::to(base64_decode($input['email']))->send(new \App\Mail\MyTestMail($details));

                        //$success['code'] = (string)$code;
                        $response = [
                            'success' => "2",
                            'message' => "email is not verified.",
                            "data" => ["code" => (string)$code]
                        ];
                        return response()->json($response, 404); */
                        return $this->sendError('Incorrect password.');
                    }
                }else{
                    return $this->sendError('please enter your password.');
                }
            }else{
                //if(User::where(['email'=> base64_decode($input['email']),'login_type'=>$input['loginType']])->exists()){
                    $user_id = $user->id;
                    $api_token = Str::random(60);

                    $os_type = isset($input['os']) ? $input['os'] : $user->os;
                    $device_id = isset($input['deviceId']) ? $input['deviceId'] : $user->device_id;

                    User::where('id', $user_id)->update(['api_token' => $api_token,'login_type'=>$input['loginType'],
                    'device_id'=>$device_id,'os'=>$os_type]);

                    $success['token'] = $api_token;
                    $success['name'] = $user->name;
                    $success['email'] = $user->email;
                    $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('d/m/Y');
                    $success['dial_code'] = $user->dial_code;
                    $success['phone'] = $user->phone;
                    //$success['authentication'] = "1";
                    $success['login_type'] = $input['loginType'];
                    $success['profile_pic'] = $user->profile_pic;
                    return $this->sendResponse($success, 'User login successfully.');
                /* }else{
                    return $this->sendError('email is register with another login account');
                } */

            }
        }else{
            $api_token = Str::random(60);

            if ($input['loginType']=="email" && !isset($input['password'])) {
                return $this->sendError('Validation Error.', ['password'=>['The password field is required.']], 401);
            }
            //$code = random_int(1000, 9999);
            $input['name'] = isset($input['name']) ? $input['name'] : '';
            $input['email'] = base64_decode($input['email']);
            $input['password'] = isset($input['password']) ? bcrypt(base64_decode($input['password'])) : '';
            $input['phone'] = isset($input['phone']) ? base64_decode($input['phone']) : NULL;
            $input['dob'] = isset($input['dob']) ? Carbon::createFromFormat('d/m/Y', $input['dob'])->format('Y-m-d') : NULL;
            $input['app_version'] = isset($input['appVersion']) ? $input['appVersion'] : NULL;
            $input['login_type'] = isset($input['loginType']) ? $input['loginType'] : NULL;
            $input['profile_pic'] = isset($input['profilePic']) ? $input['profilePic'] : NULL;
            $input['fb_provider_id'] = isset($input['providerId']) ? $input['providerId'] : NULL;
            $input['device_id'] = isset($input['deviceId']) ? $input['deviceId'] : NULL;
            $input['is_email_verified'] = isset($input['isEmailVerified']) ? $input['isEmailVerified'] : NULL;
            $input['device_model'] = isset($input['deviceModel']) ? $input['deviceModel'] : NULL;
            $input['fb_uid'] = isset($input['uid']) ? $input['uid'] : NULL;
            $input['os'] = isset($input['os']) ? $input['os'] : NULL;
            $input['dial_code'] = isset($input['dialCode']) ? base64_decode($input['dialCode']) : NULL;
            //$input['reset_password_code'] = $code;
            $input['api_token'] = $api_token;
            $user = User::create($input);
            if($user){

                /* $message = 'For email verified  use this otp: '.$code;
                $details = [
                    'title' => 'Mail from stillmanapp.com',
                    'body' => $message
                ];
                Mail::to(base64_decode($input['email']))->send(new \App\Mail\MyTestMail($details)); */

                //$success['code'] = (string)$code;
                $success['token'] =  $api_token;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('d/m/Y');
                $success['dial_code'] = $user->dial_code;
                $success['phone'] = $user->phone;
                $success['login_type'] = $user->login_type;
                $success['profile_pic'] = $user->profile_pic;
                return $this->sendResponse($success, 'User register successfully.');
            }else{

            }
        }
    }

    public function catalogs(Request $request)
    {
        //$categories = Categories::all();
        $category_path = URL::to('/uploads/category_imgs');
        $categories = DB::table('categories')->selectRaw('*,CONCAT("'.$category_path.'" "/", image) as image')->orderBy('reorder', 'ASC')->paginate(30);
        if($categories){
            $custom = collect(['success' => '1','message' => 'Categories list get successfully.']);
            $data = $custom->merge($categories);
            return response()->json($data, 200);
            //return $this->sendResponse($categories, 'Categories list get successfully.');
        }else{
            return $this->sendError('No category found.');
        }
    }

    public function products(Request $request)
    {
        $product_path = URL::to('/uploads/product_imgs');
        $products = DB::table('products')->selectRaw('*')->paginate(30);
        if($products){
            $user = Auth::user();
            foreach($products as $product){
                $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $product->id)->first();
                if($images){
                    $product->image = $images->image;
                    $product->height = $images->height;
                    $product->width = $images->width;
                }else{
                    $product->image = null;
                    $product->height = 0;
                    $product->width = 0;
                }

                $favourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=> $product->id,'status'=>1])->first();
                if(!empty($favourite)){
                    $product->is_fav = 1;
                }else{
                    $product->is_fav = 0;
                }
            }

            $custom = collect(['success' => '1','message' => 'Products list get successfully.']);
            $data = $custom->merge($products);
            return response()->json($data, 200);

            //return $this->sendResponse($products, 'Products list get successfully.');
        }else{
            return $this->sendError('No product found.');
        }
    }

    public function catelog_products(Request $request,$id)
    {
        $product_path = URL::to('/uploads/product_imgs');
        $products = DB::table('products')->selectRaw('*')->where('category_id', $id)->paginate(30);
        if($products){
            $user = Auth::user();
            foreach($products as $product){
                $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $product->id)->first();
                if($images){
                   $product->image = $images->image;
                   $product->height = $images->height;
                   $product->width = $images->width;
                }else{
                    $product->image = '';
                    $product->height = 0;
                    $product->width = 0;
                }

                $favourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=> $product->id,'status'=>1])->first();
                if(!empty($favourite)){
                    $product->is_fav = 1;
                }else{
                    $product->is_fav = 0;
                }
            }

            $custom = collect(['success' => '1','message' => 'Products list get successfully.']);
            $data = $custom->merge($products);
            return response()->json($data, 200);

            //return $this->sendResponse($products, 'Products list get successfully.');
        }else{
            return $this->sendError('No product found.');
        }
    }

    public function product_details(Request $request,$id)
    {
        $product_path = URL::to('/uploads/product_imgs');
        $product = DB::table('products')->selectRaw('*')->where('id', $id)->first();
        if($product){
            $user = Auth::user();
            $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $id)->get();
            if($images){
               $product->images = $images;
            }else{
                $product->images = [];
            }

            $favourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=> $id,'status'=>1])->first();
            if(!empty($favourite)){
                $product->is_fav = 1;
            }else{
                $product->is_fav = 0;
            }

            if($product->brand_id != 0){
                $brand = Brands::where(['id'=> $product->brand_id])->first();
                if(!empty($brand)){
                    $product->brand_name = $brand->name;
                }else{
                    $product->brand_name = '';
                }
            }else{
                $product->brand_name = '';
            }

            if($product->distillery_id != 0){
                $distillery = Distillerys::where(['id'=> $product->distillery_id])->first();
                if(!empty($distillery)){
                    $product->distillery_name = $distillery->name;
                }else{
                    $product->distillery_name = '';
                }
            }else{
                $product->distillery_name = '';
            }

            $stocks = DB::table('products')->selectRaw('*')->where('id', '!=' , $id)->where('category_id', $product->category_id)->get();
            if($stocks){
                foreach($stocks as $stock){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $stock->id)->first();
                    if($images){
                    $stock->image = $images->image;
                    $stock->height = $images->height;
                    $stock->width = $images->width;
                    }else{
                        $stock->image = '';
                        $stock->height = 0;
                        $stock->width = 0;
                    }
                }
                $product->stocks_products = $stocks;
            }else{
                $product->stocks_products = [];
            }
            return $this->sendResponse($product, 'Product details get successfully.');
        }else{
            return $this->sendError('No product found.');
        }
    }

    public function email_check(Request $request)
    {
        $input = $request->json()->all();
        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (User::where('email',$input['email'])->exists()) {
            $user = User::where('email', $input['email'])->first();

            $success['loginType'] = $user->login_type;
            $success['uid'] = $user->fb_uid;
            return $this->sendResponse($success, 'email is validate successfully!!');
        }else{
            return $this->sendError('email address not found.');
        }
    }

    public function send_otp(Request $request)
    {
        $input = $request->json()->all();
        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (!isset($input['loginType']) && empty($input['loginType'])) {
            return $this->sendError('The loginType field is required.');
        }

        if (User::where('email',$input['email'])->exists()) {
            if($input['loginType'] == "email"){
                $user = User::where(['email'=> $input['email'],'login_type'=>$input['loginType']])->first();
                if($user){
                    $code = random_int(1000, 9999);
                    User::where('id', $user->id)->update(['reset_password_code' => $code]);

                    $message = 'For reset password  use this otp: '.$code;

                    $details = [
                        'title' => 'Mail from stillmanapp.com',
                        'body' => $message
                    ];

                    Mail::to($input['email'])->send(new \App\Mail\MyTestMail($details));

                    $success['code'] = (string)$code;
                    $success['login_type'] = $user->login_type;
                    $success['uid'] = $user->fb_uid;
                    return $this->sendResponse($success, 'OTP has been sent on register email.');
                }else{
                    return $this->sendError('This email is associated with another provider login.');
                }
            }else{
                return $this->sendError('This email is associated with another provider login.');
            }
        }else{
            return $this->sendError('email address not found.');
        }
    }

    public function otp_verify(Request $request)
    {
        $input = $request->json()->all();
        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (!isset($input['code']) && empty($input['code'])) {
            return $this->sendError('The code field is required.');
        }

        if (User::where('email',$input['email'])->exists()) {
            $user = User::where(['email'=> $input['email'],'reset_password_code'=>$input['code']])->first();
            if($user){
                User::where('id', $user->id)->update(['reset_password_code' => '']);
                $success['login_type'] = $user->login_type;
                $success['uid'] = $user->fb_uid;
                return $this->sendResponse($success, 'OTP is valid.');
            }else{
                return $this->sendError('invalid OTP.');
            }
        }else{
            return $this->sendError('email address not found.');
        }
    }

    public function reset_password(Request $request)
    {
        $input = $request->json()->all();
        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (!isset($input['uid']) && empty($input['uid'])) {
            return $this->sendError('The uid field is required.');
        }

        if (!isset($input['password']) && empty($input['password'])) {
            return $this->sendError('The password field is required.');
        }

        if (User::where('email',$input['email'])->exists()) {
            $user = User::where(['email' => $input['email'],'fb_uid'=>$input['uid']])->first();
            if($user){
                User::where('id', $user->id)->update(['password' => bcrypt($input['password'])]);

                $success['login_type'] = $user->login_type;
                $success['uid'] = $user->fb_uid;
                return $this->sendResponse($success, 'Password has been reset successfully.');
            }else{
                return $this->sendError('uid not matched.');
            }
        }else{
            return $this->sendError('email address not found.');
        }
    }

    public function email_verify(Request $request)
    {
        $input = $request->json()->all();
        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (!isset($input['code']) && empty($input['code'])) {
            return $this->sendError('The code field is required.');
        }

        if (User::where('email',$input['email'])->exists()) {
            $user = User::where(['email'=> $input['email'],'reset_password_code'=>$input['code']])->first();
            if($user){

                $api_token = Str::random(60);
                User::where('id', $user->id)->update(['reset_password_code' => '','is_email_verified'=>'true','api_token' => $api_token]);

                $success['token'] = $api_token;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('d/m/Y');
                $success['dial_code'] = $user->dial_code;
                $success['phone'] = $user->phone;
                $success['profile_pic'] = $user->profile_pic;

                return $this->sendResponse($success, 'login and email verify successfully .');

                /* User::where('id', $user->id)->update(['reset_password_code' => '','is_email_verified'=>'true']);
                $success['login_type'] = $user->login_type;
                $success['uid'] = $user->fb_uid;
                return $this->sendResponse($success, 'email verify successfully.'); */
            }else{
                return $this->sendError('invalid OTP.');
            }
        }else{
            return $this->sendError('email address not found.');
        }
    }

    public function resend_email_otp(Request $request)
    {
        $input = $request->json()->all();
        if (!isset($input['email']) && empty($input['email'])) {
            return $this->sendError('The email field is required.');
        }

        if (!isset($input['loginType']) && empty($input['loginType'])) {
            return $this->sendError('The loginType field is required.');
        }

        if (User::where('email',$input['email'])->exists()) {
            if($input['loginType'] == "email"){
                $user = User::where(['email'=> $input['email'],'login_type'=>$input['loginType']])->first();
                if($user){
                    $code = random_int(1000, 9999);
                    User::where('id', $user->id)->update(['reset_password_code' => $code]);

                    $message = 'For email verified  use this otp: '.$code;

                    $details = [
                        'title' => 'Mail from stillmanapp.com',
                        'body' => $message
                    ];

                    Mail::to($input['email'])->send(new \App\Mail\MyTestMail($details));

                    $success['code'] = (string)$code;
                    $success['login_type'] = $user->login_type;
                    $success['uid'] = $user->fb_uid;
                    return $this->sendResponse($success, 'OTP has been sent on register email.');
                }else{
                    return $this->sendError('This email is associated with another provider login.');
                }
            }else{
                return $this->sendError('This email is associated with another provider login.');
            }
        }else{
            return $this->sendError('email address not found.');
        }
    }

    public function filter_products(Request $request)
    {
        $input = $request->json()->all();
        $category_path = URL::to('/uploads/category_imgs');
        //$data = [];

        $validator = Validator::make($request->json()->all(), [
            'type' => [
                'required',
                Rule::in(['0','1']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid type field');
        }

        if (!isset($input['type']) && empty($input['type'])) {
            return $this->sendError('The type field is required.');
        }

        /* if (isset($input['name']) && !empty($input['name'])) {
            $name = $input['name'];
        }else{
            $name = '';
        } */

        /* if (isset($input['catalog_id']) && !empty($input['catalog_id'])) {
            $data['category_id'] = $input['catalog_id'];
        } */

        //$products = DB::table('products')->selectRaw('*')->where($data)->where('name', 'LIKE', '%' . $name . '%')->paginate(10);

/*         if($input['type'] == 0){
            $products = DB::table('products')->selectRaw('*')->paginate(10);
        } */
        $user = Auth::user();
        if (empty($input['name'])) {
            if($input['type'] == 0){
                $categories = DB::table('categories')->selectRaw('*,CONCAT("'.$category_path.'" "/", image) as image')->orderBy('reorder', 'ASC')->paginate(30);
                if($categories){
                    $custom = collect(['success' => '1','message' => 'Catalog has been list successfully.']);
                    $data = $custom->merge($categories);
                    return response()->json($data, 200);
                }else{
                    return $this->sendError('No catalog data.');
                }
            }

            if($input['type'] == 1){
                $products = DB::table('products')->selectRaw('*')->paginate(30);
                if(!$products->isEmpty()){
                    $product_path = URL::to('/uploads/product_imgs');
                    foreach($products as $product){
                        $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $product->id)->first();
                        if($images){
                            $product->image = $images->image;
                            $product->height = $images->height;
                            $product->width = $images->width;
                        }else{
                            $product->image = null;
                            $product->height = 0;
                            $product->width = 0;
                        }

                        $favourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=> $product->id,'status'=>1])->first();
                        if(!empty($favourite)){
                            $product->is_fav = 1;
                        }else{
                            $product->is_fav = 0;
                        }
                    }

                    $custom = collect(['success' => '1','message' => 'Product has been list successfully.']);
                    $data = $custom->merge($products);
                    return response()->json($data, 200);
                }else{
                    return $this->sendError('No product matched.');
                }

            }
        }
        //echo 'sdf';die;

        if($input['type'] == 0 && !empty($input['name'])){
            $name = $input['name'];
            $categories = DB::table('categories')->selectRaw('*,CONCAT("'.$category_path.'" "/", image) as image')->where('name', 'LIKE', '%' . $name . '%')->orderBy('reorder', 'ASC')->paginate(30);
            if(!$categories->isEmpty()){
                $custom = collect(['success' => '1','message' => 'Catalog result has been filter successfully.']);
                $data = $custom->merge($categories);
                return response()->json($data, 200);
            }else{
                return $this->sendError('No catalog result matched.');
            }
        }

        if($input['type'] == 1 && !empty($input['name'])){
            $name = $input['name'];
            $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->paginate(30);
            if(!$products->isEmpty()){
                $product_path = URL::to('/uploads/product_imgs');
                foreach($products as $product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $product->id)->first();
                    if($images){
                        $product->image = $images->image;
                        $product->height = $images->height;
                        $product->width = $images->width;
                    }else{
                        $product->image = null;
                        $product->height = 0;
                        $product->width = 0;
                    }

                    $favourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=> $product->id,'status'=>1])->first();
                    if(!empty($favourite)){
                        $product->is_fav = 1;
                    }else{
                        $product->is_fav = 0;
                    }
                }

                $custom = collect(['success' => '1','message' => 'Product has been filter successfully.']);
                $data = $custom->merge($products);
                return response()->json($data, 200);
            }else{
                return $this->sendError('No product matched.');
            }
        }
    }

    public function edit_profile(Request $request)
    {
        $input = $request->json()->all();
        $user = Auth::user();

        $name = isset($input['name']) ? $input['name'] : $user->name;
        $phone = isset($input['phone']) ? $input['phone'] : $user->phone;
        $dob = isset($input['dob']) ? Carbon::createFromFormat('m/d/Y', $input['dob'])->format('Y-m-d') : $user->dob;
        $dial_code = isset($input['dialCode']) ? $input['dialCode'] : $user->dialCode;

        User::where('id', $user->id)->update(['name' => $name,'phone' => $phone,'dob'=>$dob,'dial_code'=>$dial_code]);

        //echo Carbon::createFromFormat('Y-m-d', $dob)->format('d/m/Y');die;

        $success['name'] = $name;
        $success['email'] = $user->email;
        $success['dob'] = Carbon::createFromFormat('Y-m-d', $dob)->format('m/d/Y');
        $success['dial_code'] = $dial_code;
        $success['phone'] = $phone;
        $success['profile_pic'] = $user->profile_pic;
        return $this->sendResponse($success, 'User profile has been update successfully!!');
    }

    public function edit_profile_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        if($request->hasFile('image')){
            try{
                $base_url = URL::to('');
                $destinationPath = 'uploads/user_pics';
                $imgName = $request->image->getClientOriginalName();
                $ext = explode('?', \File::extension($imgName));
                $main_ext = $ext[0];
                $finalName = time()."_".rand(1,10000).'.'.$main_ext;
                $request->image->move($destinationPath, $finalName);
                $path = $base_url.'/'.$destinationPath.'/'.$finalName;

                $user = Auth::user();

                User::where('id', $user->id)->update(['profile_pic' => $path]);
                $success['profile_pic'] = $path;
                return $this->sendResponse($success, 'Profile image updated successfully.');
            }catch (\Execption $e) {
                //$response['message'] = $e->getMessage()->withInput();
                return $this->sendError($e->getMessage()->withInput());
            }
        }else{
            return $this->sendError('The image field is required');
        }
    }

    public function cart(Request $request)
    {
        $input = $request->json()->all();
        $user = Auth::user();

        $validator = Validator::make($request->json()->all(), [
            'is_delete' => [
                'required',
                Rule::in(['0', '1']),
            'product_id' => ['required']
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        if (Products::where('id', $input['product_id'])->exists()) {
            DB::table('carts')->where(['user_id'=>$user->id,'is_order_confirmed'=>0,'is_single_cart'=>1])->delete();
            $product = Products::where('id', $input['product_id'])->first();
            $getCart = Carts::where(['user_id'=> $user->id,'product_id'=> $input['product_id'],'is_order_confirmed'=>0])->first();
            if (empty($getCart)) {
                if($input['is_delete'] == "1"){
                    return $this->sendError('this item not found in cart.');
                }

                if($product->stock < $input['quantity']){
                    return $this->sendError('No more stock.');
                }else{
                    $input['user_id'] = $user->id;
                    $cart = Carts::create($input);
                    if($cart){
                    $data = $this->common_get_cart($user);
                        if(is_array($data)){
                            $data['message'] = "Product has been added to cart successfully.";
                            return response()->json($data, 200);
                        }else{
                            return $this->sendError('cart is empty.');
                        }
                    }else{
                        return $this->sendError('Something wrong please try again.');
                    }

                }
            }else{
                if($input['is_delete'] == "1"){
                    $result = DB::table('carts')->where('id', $getCart->id)->delete();
                    if($result){
                        $data = $this->common_get_cart($user);
                        if(is_array($data)){
                            $data['message'] = "Cart item has been delete successfully.";
                            return response()->json($data, 200);
                        }else{
                            return $this->sendError('cart is empty.');
                        }
                    }else{
                        return $this->sendError('Something wrong please try again.');
                    }
                }

                if(isset($input['update_type']) && $input['update_type'] == "1"){
                    if($getCart->quantity >1){
                        $quantity = $getCart->quantity - $input['quantity'];
                        Carts::where('id', $getCart->id)->update(['quantity' => $quantity]);
                    }else{
                        $result = DB::table('carts')->where('id', $getCart->id)->delete();
                    }
                }else{
                    $quantity = $getCart->quantity + $input['quantity'];
                    if($product->stock < $quantity){
                        return $this->sendError('No more stock.');
                    }else{
                        $quantity = $getCart->quantity + $input['quantity'];
                        Carts::where('id', $getCart->id)->update(['quantity' => $quantity]);
                    }
                }


                $data = $this->common_get_cart($user);
                if(is_array($data)){
                    $data['message'] = "Cart has been updated successfully.";
                    return response()->json($data, 200);
                }else{
                    return $this->sendError('cart is empty.');
                }

                /* $data = $this->common_get_cart($user);
                echo "<pre>";print_r($data);die;
                $success['cart_id'] = $getCart->id;
                $success['product_id'] = $input['product_id'];
                $success['quantity'] = $quantity;
                return $this->sendResponse($success, 'Cart has been updated successfully.'); */
            }
        }else{
            return $this->sendError('Invalid product id');
        }
    }

    public function get_cart(Request $request)
    {
        $user = Auth::user();
        $getCart = Carts::select('id','product_id','quantity')->where(['user_id'=> $user->id,'is_order_confirmed'=>0,'is_single_cart'=>0])->get();
        if (!$getCart->isEmpty()) {
            $total = 0;
            $sub_total = 0;
            $tax = "8%";
            $service_fee = "2.95%";

            $product_path = URL::to('/uploads/product_imgs');
            foreach($getCart as $cart){
                $cart->image = null;
                $cart->height = 0;
                $cart->width = 0;
                $cart->name = '';
                $cart->price = '0';

                $product = DB::table('products')->selectRaw('*')->where('id', $cart->product_id)->first();
                if($product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $cart->product_id)->first();
                    $cart->name = $product->name;
                    $cart->price = $product->price;
                    $cart->stock = $product->stock;
                    $cart->size_of_item = $product->size_of_item;
                    if($images){
                        $cart->image = $images->image;
                        $cart->height = $images->height;
                        $cart->width = $images->width;
                    }
                }

                $sub_total=$sub_total+($product->price*$cart->quantity);
                //$total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.9)/100);
                if($sub_total<100){
                    $tax_amount = (($sub_total*8)/100);
                    $service_amount = (($sub_total*2.95)/100);
                    $total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.95)/100);
                }else{
                    $tax_amount = (($sub_total*8)/100);
                    $service_amount = (($sub_total*2.9)/100);
                    $total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.9)/100);
                    $service_fee = "2.9%";
                }
            }

            return response()->json(['success' => "1",
                                    'message' => "Cart has been get successfully.",
                                    'sub_total' => number_format(floor($sub_total*100)/100,2, '.', ''),
                                    'tax_amount' => $tax_amount,
                                    'tax' => $tax,
                                    'service_amount' => $service_amount,
                                    'service_fee' => $service_fee,
                                    'total' => number_format(floor($total*100)/100,2, '.', ''),
                                    'success' => "1",
                                    'data'=>$getCart], 200);
            //return $this->sendResponse($getCart, 'Cart has been get successfully.');
        }else{
            return $this->sendError('cart is empty!');
        }
    }

    public function common_get_cart($user)
    {
        $getCart = Carts::select('id','product_id','quantity')->where(['user_id'=> $user->id,'is_order_confirmed'=>0,'is_single_cart'=>0])->get();
        if (!$getCart->isEmpty()) {
            $total = 0;
            $sub_total = 0;
            $tax = "8%";
            $service_fee = "2.95%";


            $product_path = URL::to('/uploads/product_imgs');
            foreach($getCart as $cart){
                $cart->image = null;
                $cart->height = 0;
                $cart->width = 0;
                $cart->name = '';
                $cart->price = '0';

                $product = DB::table('products')->selectRaw('*')->where('id', $cart->product_id)->first();
                if($product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $cart->product_id)->first();
                    $cart->name = $product->name;
                    $cart->price = $product->price;
                    $cart->stock = $product->stock;
                    $cart->size_of_item = $product->size_of_item;
                    if($images){
                        $cart->image = $images->image;
                        $cart->height = $images->height;
                        $cart->width = $images->width;
                    }
                }

                $sub_total=$sub_total+($product->price*$cart->quantity);
                if($sub_total<100){
                    $tax_amount = number_format(floor($sub_total*8)/100,2, '.', '');
                    $service_amount = number_format(floor($sub_total*2.95)/100,2, '.', '');
                    $total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.95)/100);
                }else{
                    $tax_amount = number_format(floor($sub_total*8)/100,2, '.', '');
                    $service_amount = number_format(floor($sub_total*2.9)/100,2, '.', '');
                    $total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.9)/100);
                    $service_fee = "2.9%";
                }
            }

            return ['success' => "1",
                    'message' => "",
                    'sub_total' => number_format(floor($sub_total*100)/100,2, '.', ''),
                    'tax_amount' => $tax_amount,
                    'tax' => $tax,
                    'service_amount' => $service_amount,
                    'service_fee' => $service_fee,
                    'total' => number_format(floor($total*100)/100,2, '.', ''),
                    'success' => "1",
                    'data'=>$getCart];
            //return $this->sendResponse($getCart, 'Cart has been get successfully.');
        }else{
            //return $this->sendError('cart is empty!');
            return false;
        }
    }

    public function common_get_single_cart($user)
    {
        $getCart = Carts::select('id','product_id','quantity')->where(['user_id'=> $user->id,'is_order_confirmed'=>0,'is_single_cart'=>1])->get();
        if (!$getCart->isEmpty()) {
            $total = 0;
            $sub_total = 0;
            $tax = "8%";
            $service_fee = "2.95%";


            $product_path = URL::to('/uploads/product_imgs');
            foreach($getCart as $cart){
                $cart->image = null;
                $cart->height = 0;
                $cart->width = 0;
                $cart->name = '';
                $cart->price = '0';

                $product = DB::table('products')->selectRaw('*')->where('id', $cart->product_id)->first();
                if($product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $cart->product_id)->first();
                    $cart->name = $product->name;
                    $cart->price = $product->price;
                    $cart->stock = $product->stock;
                    $cart->size_of_item = $product->size_of_item;
                    if($images){
                        $cart->image = $images->image;
                        $cart->height = $images->height;
                        $cart->width = $images->width;
                    }
                }

                $sub_total=$sub_total+($product->price*$cart->quantity);
                if($sub_total<100){
                    $tax_amount = number_format(floor($sub_total*8)/100,2, '.', '');
                    $service_amount = number_format(floor($sub_total*2.95)/100,2, '.', '');
                    $total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.95)/100);
                }else{
                    $tax_amount = number_format(floor($sub_total*8)/100,2, '.', '');
                    $service_amount = number_format(floor($sub_total*2.9)/100,2, '.', '');
                    $total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.9)/100);
                    $service_fee = "2.9%";
                }
                //$total=$sub_total+(($sub_total*8)/100)+(($sub_total*2.9)/100);
            }

            return ['success' => "1",
                    'message' => "",
                    'sub_total' => number_format(floor($sub_total*100)/100,2, '.', ''),
                    'tax_amount' => $tax_amount,
                    'tax' => $tax,
                    'service_amount' => $service_amount,
                    'service_fee' => $service_fee,
                    'total' => number_format(floor($total*100)/100,2, '.', ''),
                    'success' => "1",
                    'is_single_cart'=> "1",
                    'data'=>$getCart];
            //return $this->sendResponse($getCart, 'Cart has been get successfully.');
        }else{
            //return $this->sendError('cart is empty!');
            return false;
        }
    }

    public function change_password(Request $request)
    {
        //$input = $request->json()->all();
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'old_password' => ['required'],
            'new_password' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }
        $user = Auth::user();

        if($user->login_type=="email"){
            if (Hash::check($request->input('old_password'), $user->password)) {
                $api_token = Str::random(60);

                User::where('id', $user->id)->update(['password' => bcrypt($input['new_password'])]);

                $success['token'] = $api_token;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['dob'] = Carbon::createFromFormat('Y-m-d', $user->dob)->format('d/m/Y');
                $success['login_type'] = $user->login_type;
                $success['phone'] = $user->phone;
                return $this->sendResponse($success, 'Password has been change successfully.');
            }else{
                return $this->sendError('Invailid old password!');
            }
        }else{
            return $this->sendError('login type is not email!');
        }
    }

    public function add_address(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'dialCode' => ['required'],
            'addressline1' => ['required'],
            'addressline2' => ['required'],
            'pincode' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }
        $user = Auth::user();
        $check = UserAddresses::where('user_id', $user->id)->first();
        if(!empty($check)){
            $input['default'] = "0";
        }else{
            $input['default'] = "1";
        }

        $input['nearby'] = isset($input['nearby']) ? $input['nearby'] : NULL;
        $input['user_id'] = $user->id;
        $input['dial_code'] = $input['dialCode'];
        $address = UserAddresses::create($input);
        if($address){
            $success['address_id'] = $address->id;
            return $this->sendResponse($success, 'Address has been added successfully.');
        }else{

        }
    }

    public function edit_address(Request $request,$id)
    {
        $input = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'dialCode'=> ['required'],
            'addressline1' => ['required'],
            'addressline2' => ['required'],
            'pincode' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        if (UserAddresses::where('id', $id)->exists()) {
            $user = Auth::user();
            $dial_code = $input['dialCode'];
            unset($input['dialCode']);
            //$input['dial_code'] = $input['dialCode'];
            $input['dial_code'] = $dial_code;
            $input['nearby'] = isset($input['nearby']) ? $input['nearby'] : NULL;
            $address = UserAddresses::where('id', $id)->update($input);
            if($address){
                //$success = [];
                $success['address_id'] = $id;
                return $this->sendResponse($success, 'Address has been update successfully.');
            }else{
                return $this->sendError('Something wrong please try again.');
            }
        }else{
            return $this->sendError('Invaid address id.');
        }
    }

    public function get_address(Request $request)
    {
        $user = Auth::user();
        $address = UserAddresses::where(['user_id'=> $user->id])->get();
        if(!$address->isEmpty()){
            return $this->sendResponse($address, 'Address list been get successfully.');
        }else{
            return $this->sendError('No address list found.');
        }
    }

    public function delete_address(Request $request,$id)
    {
        if (UserAddresses::where('id', $id)->exists()) {
            $user = Auth::user();
            $getaddress = UserAddresses::where('id', $id)->first();

            if($getaddress->default == 1){
                $is_default_delete = 1;
            }else{
                $is_default_delete = 0;
            }

            $result = DB::table('user_addresses')->where(['id'=> $id,'user_id'=>$user->id])->delete();
            if($result){
                if($is_default_delete == 1){
                    $nextaddress = DB::table('user_addresses')->where(['user_id'=>$user->id])->first();
                    if(!empty($nextaddress)){
                        $input['default'] = 1;//make auto default
                        UserAddresses::where('id', $nextaddress->id)->update($input);
                    }
                }

                $address = UserAddresses::where(['user_id'=> $user->id])->get();
                if(!$address->isEmpty()){
                    return $this->sendResponse($address, 'Address has been delete successfully.');
                }else{
                    $success = [];
                    return $this->sendResponse($success, 'No address list here.');
                    //return $this->sendError('No address list here.');
                }
            }else{
                return $this->sendError('Something wrong please try again.');
            }
        }else{
            return $this->sendError('Invaid address id.');
        }
    }

    public function default_address(Request $request)
    {
        $input = $request->json()->all();
        if (UserAddresses::where('id', $input['address_id'])->exists()) {
            $user = Auth::user();
            UserAddresses::where('user_id', $user->id)->update(['default'=>0]);//remove default

            $result = UserAddresses::where('id', $input['address_id'])->update(['default'=>1]);
            if($result){
                $address = UserAddresses::where(['user_id'=> $user->id])->get();
                if(!$address->isEmpty()){
                    return $this->sendResponse($address, 'Address has been default successfully.');
                }else{
                    return $this->sendError('No address list here.');
                }
            }else{
                return $this->sendError('Something wrong please try again.');
            }
        }else{
            return $this->sendError('Invaid address id.');
        }
    }

    public function add_favourite(Request $request)
    {
        $input = $request->json()->all();
        if (Products::where('id', $input['product_id'])->exists()) {
            $user = Auth::user();
            $checkFavourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=>$input['product_id']])->first();
            if(empty($checkFavourite)){
                $input['user_id'] = $user->id;
                $input['status'] = 1;
                $result = ProductFavourite::create($input);
                if($result){
                    $success = [];
                    return $this->sendResponse($success, 'Product has been added in favorite list successfully.');
                }else{
                    return $this->sendError('Something wrong please try again.');
                }
            }else{
                if($checkFavourite->status==1){
                    $status = 0;
                    $message = 'Product has been removed from favorite list.';
                }else{
                    $status = 1;
                    $message = 'Product has been added in favorite list successfully.';
                }

                $input['status'] = $status;
                $result = ProductFavourite::where('id', $checkFavourite->id)->update($input);
                if($result){
                    $success = [];
                    return $this->sendResponse($success, $message);
                }else{
                    return $this->sendError('Something wrong please try again.');
                }
            }
        }else{
            return $this->sendError('Invalid product id');
        }
    }

    public function get_favourite(Request $request)
    {
        $user = Auth::user();
        $product_path = URL::to('/uploads/product_imgs');
        $favourites = ProductFavourite::select('product_id')->where(['user_id'=> $user->id,'status'=>1])->paginate(30);
        if(!$favourites->isEmpty()){
            //echo "<pre>";print_r($favourites);die;
            foreach($favourites as $favourite){
                $product = DB::table('products')->selectRaw('*')->where('id', $favourite->product_id)->first();
                $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $favourite->product_id)->first();

                $favourite->id = $favourite->product_id;
                $favourite->name = $product->name;
                $favourite->price = $product->price;
                $favourite->stock = $product->stock;
                $favourite->size_of_item = $product->size_of_item;
                $favourite->is_fav = 1;
                if($images){
                    $favourite->image = $images->image;
                    $favourite->height = $images->height;
                    $favourite->width = $images->width;
                }else{
                    $favourite->image = null;
                    $favourite->height = 0;
                    $favourite->width = 0;
                }
                //unset($favourite->product_id);
            }

            $custom = collect(['success' => '1','message' => 'Favorite list get successfully.']);
            $data = $custom->merge($favourites);
            return response()->json($data, 200);
        }else{
            return $this->sendError('No favourite list found');
        }
    }

    public function get_default_address(Request $request)
    {
        $user = Auth::user();
        $address = UserAddresses::where(['user_id'=> $user->id,'default'=>1])->first();
        if(!empty($address)){
            return $this->sendResponse($address, 'Get address has been get successfully.');
        }else{
            return $this->sendError('No default address here.');
        }
    }

    public function device_register(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [
            'type' => [
                'required',
                Rule::in(['ios','android']),
            ],
            'token' => ['required']
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        $user = Auth::user();
        if (DeviceRegister::where('user_id', $user->id)->exists()) {
            $device = DeviceRegister::where(['user_id'=>$user->id])->first();

            $response = DeviceRegister::where(['user_id'=>$user->id,'id'=>$device->id])->update(['type'=>$input['type'],'token'=>$input['token']]);
            if($response){
                $success['device_id'] = $device->id;
                return $this->sendResponse($success, 'Device has been update successfully!');
            }else{
                return $this->sendError('Something wrong please try again.');
            }
        }else{
            $response = DeviceRegister::create(['user_id'=>$user->id,'type'=>$input['type'],'token'=>$input['token']]);
            if($response){
                $success['device_id'] = $response->id;
                return $this->sendResponse($success, 'Device has been Register successfully!');
            }else{
                return $this->sendError('Something wrong please try again.');
            }
        }
    }

    public function get_notifications(Request $request){
        $user = Auth::user();
        if (Notification::where(['user_id'=> $user->id,'is_read'=>0])->exists()) {
            $notifications = DB::table('notifications AS n')
                    ->rightJoin('products AS p', 'n.product_id', '=', 'p.id')
                    ->select('n.id','n.product_id','n.user_id','n.shipment_status', 'p.name','p.description','n.created_at as notification_date')
                    ->where('n.user_id', $user->id)
                    ->where('n.is_read', 0)
                    ->orderBy('n.id', 'DESC')
                    ->paginate(20);
            foreach($notifications as $notification){
                $notification->shipment_status_msg = 'Processing Order';
            }

            $custom = collect(['success' => '1','message' => 'Notification list has been get successfully!']);
            $data = $custom->merge($notifications);
            return response()->json($data, 200);
            //return $this->sendResponse($notifications, 'Notification list has been get successfully!');
        }else{
            return $this->sendError('No notification here.');
        }
    }

    public function notify_user(Request $request){
        //$user = User::where('id', $request->id)->first();

        /* $user = Auth::user();
        $notification_id = 'token';
        $title = "Greeting Notification";
        $message = "Have good day!";
        $id = $user->id;
        $type = "basic";
        $res = $this->send_notification_FCM($notification_id, $title, $message, $id,$type);
        if($res == 1){
            echo "<pre>";print_r($res);die;
           // success code
        }else{
            echo "<pre>hhh";print_r($res);die;
          // fail code
        } */

        /* $regId =$_POST["nId"];
        $dType =$_POST["device_type"]; */

        // INCLUDE YOUR FCM FILE
        //include_once 'fcm.php';

        $regId = 'f7wLQVg1sU3tkoAZQKNIN6:APA91bFP16U58x_ls7UH0ndvssJcYZAKDGVjyA2OEv6Oe78kBYPUU64o9NMalQZCV5qXMUwpjUo1CYR1gWDrIrNNABxvxvsdJcaEco8Z4mmiTOxrnhoIjMnSw0C-4FUQpVn9MirOCt7C';
        $dType =  'IOS';

        $arrNotification= array();

        $arrNotification["body"] ="Greeting Notification";
        $arrNotification["title"] = "Have good day!";
        $arrNotification["sound"] = "default";
        $arrNotification["type"] = 1;

        //$fcm = new FCM();
        $result = $this->send_notification($regId, $arrNotification,$dType);
        if($result == 1){
            echo "<pre>";print_r($result);die;
           // success code
        }else{
            echo "<pre>hhh";print_r($result);die;
          // fail code
        }
        echo "<pre>";print_r($result);die;
    }

    public function send_notification($registatoin_ids, $notification,$device_type) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        if($device_type == "Android"){
              $fields = array(
                  'to' => $registatoin_ids,
                  'data' => $notification
              );
        } else {
              $fields = array(
                  'to' => $registatoin_ids,
                  'notification' => $notification
              );
        }
        // Firebase API Key
        $headers = array('Authorization:key=AAAAUm78OT4:APA91bGVen6aD4Pv0L_HRM1va69FlfCRPOm5Fu6KIqcRtDLq2J3ReyIvp-TQ3YogsQ5IRHXnlp1fAHJGWRJEe9XoFAnEXz70YKSeoq8sqZdw4GsfRSzZ8Np7T_8AsqFAMUCcjNYTUPUD','Content-Type:application/json');
       // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //echo "<pre>";print_r($result);die;
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        if ($result === false) {
            // throw new Exception('Curl error: ' . curl_error($crl));
            //print_r('Curl error: ' . curl_error($crl));
            $result_noti = 0;
        } else {
            $result_noti = 1;
        }

        curl_close($crl);
        return $result_noti;
    }

    function send_notification_FCM($notification_id, $title, $message, $id,$type) {

        $accesstoken = 'AAAAUm78OT4:APA91bGVen6aD4Pv0L_HRM1va69FlfCRPOm5Fu6KIqcRtDLq2J3ReyIvp-TQ3YogsQ5IRHXnlp1fAHJGWRJEe9XoFAnEXz70YKSeoq8sqZdw4GsfRSzZ8Np7T_8AsqFAMUCcjNYTUPUD';
        //echo 'hii'.$accesstoken;die;

        $URL = 'https://fcm.googleapis.com/fcm/send';


            $post_data = '{
                "to" : "' . $notification_id . '",
                "data" : {
                    "body" : "",
                    "title" : "' . $title . '",
                    "type" : "' . $type . '",
                    "id" : "' . $id . '",
                    "message" : "' . $message . '",
                },
                "notification" : {
                        "body" : "' . $message . '",
                        "title" : "' . $title . '",
                        "type" : "' . $type . '",
                        "id" : "' . $id . '",
                        "message" : "' . $message . '",
                    "icon" : "new",
                    "sound" : "default"
                    },

                }';
            // print_r($post_data);die;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: ' . $accesstoken;
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($crl, CURLOPT_URL, $URL);
        curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

        $rest = curl_exec($crl);
        echo "<pre>";print_r($rest);die;

        if ($rest === false) {
            // throw new Exception('Curl error: ' . curl_error($crl));
            //print_r('Curl error: ' . curl_error($crl));
            $result_noti = 0;
        } else {

            $result_noti = 1;
        }

        //curl_close($crl);
        //print_r($result_noti);die;
        return $result_noti;
    }

    public function get_lifestyles(Request $request){
        $lifestyles = Lifestyles::paginate(30);
        //echo "<pre>";print_r($lifestyles);die;
        if(!$lifestyles->isEmpty()){
            $product_path = URL::to('/uploads/lifestyle_imgs');
            foreach($lifestyles as $lifestyle){
                $lifestyle->image = $product_path.'/'.$lifestyle->image;
                if($lifestyle->type=='product'){
                    $lifestyle->api_url =  URL::to('/api/product/'.$lifestyle->type_id.'/details');
                }else if($lifestyle->type=='category'){
                    $lifestyle->api_url =  URL::to('/api/catalog/'.$lifestyle->type_id.'/products');
                }else if($lifestyle->type=='product_page'){
                    $lifestyle->api_url =  URL::to('/api/products');
                }else{
                    $lifestyle->api_url = URL::to('/api/products');
                }
            }
            //return $this->sendResponse($lifestyles, 'Lifestyle list get successfully.');

            $custom = collect(['success' => '1','message' => 'Lifestyle list get successfully.']);
            $data = $custom->merge($lifestyles);
            return response()->json($data, 200);
        }else{
            return $this->sendError('No favourite list found');
        }
    }

    public function get_listings(Request $request)
    {
        $category_path = URL::to('/uploads/category_imgs');
        $categories = DB::table('categories')->selectRaw('id,name')->orderBy('reorder', 'ASC')->get();
        $brands = DB::table('brands')->selectRaw('id,name')->orderBy('id', 'ASC')->get();
        $sizes = Products::select('size_of_item as size')->distinct()->where('size_of_item', '<>', 0)->get();
        $ages = Products::select('age_statement as age')->distinct()->where('age_statement', '<>', 0)->get();
        $years = Products::select('year_of_release as year')->distinct()->where('year_of_release', '<>', 0)->get();
        //$sizes = Products::select('size_of_item as size')->distinct()->get();
        //$ages = Products::select('age_statement as age')->distinct()->get();
        //$years = Products::select('year_of_release as year')->distinct()->get();
        $maxPrice = Products::max('price');
        $minPrice = Products::min('price');
        if($categories){
            $success['min_price'] = $minPrice;
            $success['max_price'] = $maxPrice;
            $success['categories'] = $categories;
            $success['brands'] = $brands;
            $success['sizes'] = $sizes;
            $success['ages'] = $ages;
            $success['years'] = $years;
            $custom = collect(['success' => '1','message' => 'list items has been get successfully!!.']);
            $data = $custom->merge($success);
            return response()->json($data, 200);
        }else{
            return $this->sendError('No list items found.');
        }
    }

    public function filter_new_products(Request $request)
    {
        $input = $request->json()->all();
        $category_path = URL::to('/uploads/category_imgs');
        //$data = [];

        $validator = Validator::make($request->json()->all(), [
            'type' => [
                'required',
                Rule::in(['0','1']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid type field');
        }

        if (!isset($input['type']) && empty($input['type'])) {
            return $this->sendError('The type field is required.');
        }

        $user = Auth::user();
        if ($input['type'] == 0 && empty($input['name'])) {
            if($input['type'] == 0){
                $categories = DB::table('categories')->selectRaw('*,CONCAT("'.$category_path.'" "/", image) as image')->orderBy('reorder', 'ASC')->paginate(30);
                if($categories){
                    $custom = collect(['success' => '1','message' => 'Catalog has been list successfully.']);
                    $data = $custom->merge($categories);
                    return response()->json($data, 200);
                }else{
                    return $this->sendError('No catalog data.');
                }
            }
        }

        if($input['type'] == 0 && !empty($input['name'])){
            $name = $input['name'];
            $categories = DB::table('categories')->selectRaw('*,CONCAT("'.$category_path.'" "/", image) as image')->where('name', 'LIKE', '%' . $name . '%')->orderBy('reorder', 'ASC')->paginate(30);
            if(!$categories->isEmpty()){
                $custom = collect(['success' => '1','message' => 'Catalog result has been filter successfully.']);
                $data = $custom->merge($categories);
                return response()->json($data, 200);
            }else{
                return $this->sendError('No catalog result matched.');
            }
        }

        if($input['type'] == 1){
            $filterArr = [];
            if(isset($input['name']) && !empty($input['name'])){
                $name = $input['name'];
            }else{
                $name = '';
            }

            if(isset($input['max_price']) && !empty($input['max_price'])){
                $max_price = $input['max_price'];
            }else{
                $max_price = Products::max('price');
            }

            if(isset($input['min_price']) && !empty($input['min_price'])){
                $min_price = $input['min_price'];
            }else{
                $min_price = Products::min('price');
            }

            if(isset($input['category_id']) && !empty($input['category_id'])){
                $filterArr['category_id']=$input['category_id'];
            }

            if(isset($input['brand_id']) && !empty($input['brand_id'])){
                $filterArr['brand_id']=$input['brand_id'];
            }

            if(isset($input['age']) && !empty($input['age'])){
                $filterArr['age_statement']=$input['age'];
            }

            if(isset($input['year']) && !empty($input['year'])){
                $filterArr['year_of_release']=$input['year'];
            }

            if(isset($input['size']) && !empty($input['size'])){
                $filterArr['size_of_item']=$input['size'];
            }

            $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->whereBetween('price', [$min_price, $max_price])->where($filterArr)->paginate(30);
            if(!$products->isEmpty()){
                $product_path = URL::to('/uploads/product_imgs');
                foreach($products as $product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $product->id)->first();
                    if($images){
                        $product->image = $images->image;
                        $product->height = $images->height;
                        $product->width = $images->width;
                    }else{
                        $product->image = null;
                        $product->height = 0;
                        $product->width = 0;
                    }

                    $favourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=> $product->id,'status'=>1])->first();
                    if(!empty($favourite)){
                        $product->is_fav = 1;
                    }else{
                        $product->is_fav = 0;
                    }
                }

                $custom = collect(['success' => '1','message' => 'Product has been filter successfully.']);
                $data = $custom->merge($products);
                return response()->json($data, 200);
            }else{
                return $this->sendError('No product matched.');
            }
        }
    }

    public function filter_multi_products(Request $request)
    {
        $input = $request->json()->all();
        $category_path = URL::to('/uploads/category_imgs');
        $sort_filter = '';
        //$data = [];

        $validator = Validator::make($request->json()->all(), [
            'type' => [
                'required',
                Rule::in(['0','1']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid type field');
        }

        if (!isset($input['type']) && empty($input['type'])) {
            return $this->sendError('The type field is required.');
        }

        $user = Auth::user();
        if ($input['type'] == 0 && empty($input['name'])) {
            if($input['type'] == 0){
                $categories = DB::table('categories')->selectRaw('*,CONCAT("'.$category_path.'" "/", image) as image')->orderBy('reorder', 'ASC')->paginate(30);
                if($categories){
                    $custom = collect(['success' => '1','message' => 'Catalog has been list successfully.']);
                    $data = $custom->merge($categories);
                    return response()->json($data, 200);
                }else{
                    return $this->sendError('No catalog data.');
                }
            }
        }

        if($input['type'] == 0 && !empty($input['name'])){
            $name = $input['name'];
            $categories = DB::table('categories')->selectRaw('*,CONCAT("'.$category_path.'" "/", image) as image')->where('name', 'LIKE', '%' . $name . '%')->orderBy('reorder', 'ASC')->paginate(30);
            if(!$categories->isEmpty()){
                $custom = collect(['success' => '1','message' => 'Catalog result has been filter successfully.']);
                $data = $custom->merge($categories);
                return response()->json($data, 200);
            }else{
                return $this->sendError('No catalog result matched.');
            }
        }

        if($input['type'] == 1){
            $filterArr = [];
            if(isset($input['name']) && !empty($input['name'])){
                $name = $input['name'];
            }else{
                $name = '';
            }

            if(isset($input['max_price']) && !empty($input['max_price'])){
                $max_price = $input['max_price'];
            }else{
                $max_price = Products::max('price');
            }

            if(isset($input['min_price']) && !empty($input['min_price'])){
                $min_price = $input['min_price'];
            }else{
                $min_price = Products::min('price');
            }

            if(isset($input['category_id']) && !empty($input['category_id'])){
                $filterArr['category_id']= $input['category_id'];
                $category_ids = explode (",", $input['category_id']);
            }else{
                if(!empty($input['brand_id'])){
                    $category_ids = [];
                }else{
                    $category_ids = Categories::where('id' ,'>' ,0)->pluck('id')->toArray();
                }
            }

            if(isset($input['brand_id']) && !empty($input['brand_id'])){
                $filterArr['brand_id']=$input['brand_id'];
                $brand_ids = explode (",", $input['brand_id']);
            }else{
                //$brand_ids = Brands::where('id' ,'>' ,0)->pluck('id')->toArray();
                $brand_ids = [];
            }

            if(isset($input['age']) && !empty($input['age'])){
                $filterArr['age_statement']=$input['age'];
                $age_statements = explode (",", '0,'.$input['age']);
            }else{
                //$age_statements = Products::distinct()->pluck('age_statement')->toArray();
                $age_statements = explode (",", '0,10');
            }

            if(isset($input['year']) && !empty($input['year'])){
                $filterArr['year_of_release']=$input['year'];
                $year_of_releases = explode (",", '0,'.$input['year']);
            }else{
                $year_of_releases = Products::distinct()->pluck('year_of_release')->toArray();
            }

            if(isset($input['size']) && !empty($input['size'])){
                $filterArr['size_of_item']=$input['size'];
                $size_of_items = explode (",", '0,'.$input['size']);
            }else{
                $size_of_items = Products::distinct()->pluck('size_of_item')->toArray();
            }

            if(isset($input['sort']) && !empty($input['sort'])){
                if($input['sort']==1){
                    $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->whereBetween('price', [$min_price, $max_price])->whereIn('category_id',$category_ids)->orWhereIn('brand_id',$brand_ids)->whereIn('size_of_item',$size_of_items)->whereIn('year_of_release',$year_of_releases)->whereIn('age_statement',$age_statements)->orderBy('id','desc')->paginate(30);
                }elseif($input['sort']==2){
                    $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->whereBetween('price', [$min_price, $max_price])->whereIn('category_id',$category_ids)->orWhereIn('brand_id',$brand_ids)->whereIn('size_of_item',$size_of_items)->whereIn('year_of_release',$year_of_releases)->whereIn('age_statement',$age_statements)->orderBy('id','desc')->paginate(30);
                }elseif($input['sort']==3){
                    $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->whereBetween('price', [$min_price, $max_price])->whereIn('category_id',$category_ids)->orWhereIn('brand_id',$brand_ids)->whereIn('size_of_item',$size_of_items)->whereIn('year_of_release',$year_of_releases)->whereIn('age_statement',$age_statements)->orderBy('price','asc')->paginate(30);
                }elseif($input['sort']==4){
                    $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->whereBetween('price', [$min_price, $max_price])->whereIn('category_id',$category_ids)->orWhereIn('brand_id',$brand_ids)->whereIn('size_of_item',$size_of_items)->whereIn('year_of_release',$year_of_releases)->whereIn('age_statement',$age_statements)->orderBy('price','desc')->paginate(30);
                }else{
                    $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->whereBetween('price', [$min_price, $max_price])->whereIn('category_id',$category_ids)->orWhereIn('brand_id',$brand_ids)->whereIn('size_of_item',$size_of_items)->whereIn('year_of_release',$year_of_releases)->whereIn('age_statement',$age_statements)->paginate(30);
                }
            }else{
                //DB::enableQueryLog(); // Enable query log

                $products = DB::table('products')->selectRaw('*')->where('name', 'LIKE', '%' . $name . '%')->whereBetween('price', [$min_price, $max_price])->whereIn('category_id',$category_ids)->orWhereIn('brand_id',$brand_ids)->whereIn('size_of_item',$size_of_items)->whereIn('year_of_release',$year_of_releases)->whereIn('age_statement',$age_statements)->paginate(30); 
                
                //dd(DB::getQueryLog()); // Show results of log
            }
            

            if(!$products->isEmpty()){
                $product_path = URL::to('/uploads/product_imgs');
                foreach($products as $product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $product->id)->first();
                    if($images){
                        $product->image = $images->image;
                        $product->height = $images->height;
                        $product->width = $images->width;
                    }else{
                        $product->image = null;
                        $product->height = 0;
                        $product->width = 0;
                    }

                    $favourite = ProductFavourite::where(['user_id'=> $user->id,'product_id'=> $product->id,'status'=>1])->first();
                    if(!empty($favourite)){
                        $product->is_fav = 1;
                    }else{
                        $product->is_fav = 0;
                    }
                }

                $custom = collect(['success' => '1','message' => 'Product has been filter successfully.']);
                $data = $custom->merge($products);
                return response()->json($data, 200);
            }else{
                return $this->sendError('No product matched.');
            }
        }
    }
}
