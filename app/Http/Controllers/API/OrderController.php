<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserAddresses;
use App\Models\Products;
use App\Models\Carts;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
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

class OrderController extends BaseController
{
    public $successStatus = 200;
    public function __constructor(){
        #$this->middleware('auth:api');
    }

    public function add_order(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [ 
            'is_single' => [
                'required',
                Rule::in(['0','1']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());      
        }
        
        $user = Auth::user();

        if($input['is_single'] == 1){
            $carts = Carts::where(['user_id'=> $user->id,'is_order_confirmed'=>0,'is_single_cart'=>1])->get();
        }else{
            $carts = Carts::where(['user_id'=> $user->id,'is_order_confirmed'=>0,'is_single_cart'=>0])->get();
        }
        //echo $user->id;die;
        //echo "<pre>";print_r($carts);die;
        if(!$carts->isEmpty()){
            if($input['is_single'] == 1){
                $data = app('App\Http\Controllers\API\UserController')->common_get_single_cart($user);
            }else{
                $data = app('App\Http\Controllers\API\UserController')->common_get_cart($user);
            }

            //echo "<pre>";print_r($data);die;
            
            if(is_array($data)){
                $service_amount = $data['service_amount'];
                $service_fee = $data['service_fee'];
                $tax_amount = $data['tax_amount'];
                $tax = $data['tax'];
                $sub_total = $data['sub_total'];
                $total = $data['total'];

                if($input['is_single'] == 1){
                    $is_single_cart = 1;
                }else{
                    $is_single_cart = 0;
                }

                if(isset($input['address_id']) && $input['address_id'] != 0) {
                    $address_id = $input['address_id'];
                    $is_order_confirmed = 0;
                    $payment_status = '';
                    $is_pickup = 0;
                    $shipment_status=1;
                    $pickup_date = null;
                    $pickup_time = null;
                }else{
                    if (!isset($input['pickup_date']) && empty($input['pickup_date'])) {
                        return $this->sendError('The pickup date field is required.');
                    }
                    if (!isset($input['pickup_time']) && empty($input['pickup_time'])) {
                        return $this->sendError('The pickup time field is required.');
                    }

                    $address_id = 0;
                    $is_order_confirmed = 1;
                    $payment_status = 'completed';
                    $is_pickup = 1;
                    $shipment_status=6;
                    $pickup_date = $input['pickup_date'];
                    $pickup_time = $input['pickup_time'];
                }

                $get_order = Order::where(['user_id'=> $user->id,'is_single_cart'=>$is_single_cart,'payment_status'=>'pending'])->latest()->first();
                if(!empty($get_order)){
                    Order::where(['id'=>$get_order->id,'user_id'=> $user->id])->update(['service_amount'=>$service_amount,'service_fee'=>$service_fee,'tax_amount'=>$tax_amount,'tax'=>$tax,'sub_total'=>$sub_total,'total'=>$total,'address_id'=>$address_id,'is_pickup'=>$is_pickup,'payment_status'=>$payment_status,'pickup_date'=>$pickup_date,'pickup_time'=>$pickup_time]);
                    $order_id = $get_order->id;
                    $msg = 'Order has been updated successfully.';
                }else{
                    $order = Order::create(['user_id'=>$user->id,'service_amount'=>$service_amount,'service_fee'=>$service_fee,'tax_amount'=>$tax_amount,'tax'=>$tax,'sub_total'=>$sub_total,'total'=>$total,'is_single_cart'=>$is_single_cart,'address_id'=>$address_id,'is_pickup'=>$is_pickup,'payment_status'=>$payment_status,'pickup_date'=>$pickup_date,'pickup_time'=>$pickup_time]);
                    $order_id = $order->id;
                    $msg = 'Order has been added successfully.';
                }
                //$order = Order::create(['user_id'=>$user->id,'service_fee'=>$service_fee,'tax'=>$tax,'sub_total'=>$sub_total,'total'=>$total,'is_single_cart'=>$is_single_cart]);

                if(!isset($input['address_id'])) {
                    $msg = 'Pickup order is confirmed';
                }

                if($order_id){
                    if($input['is_single'] == 1){
                        Carts::where(['user_id'=> $user->id,'is_single_cart'=>1,'is_order_confirmed'=>0])->update(['order_id' => $order_id,'is_order_confirmed'=>$is_order_confirmed,'shipment_status'=>$shipment_status]);
                    }else{
                        Carts::where(['user_id'=> $user->id,'is_single_cart'=>0,'is_order_confirmed'=>0])->update(['order_id' => $order_id,'is_order_confirmed'=>$is_order_confirmed,'shipment_status'=>$shipment_status]);
                    }
                    $success['order_id'] =  $order_id;
                    return $this->sendResponse($success, $msg);
                }else{
                    return $this->sendError('Something wrong please try again.');
                }
            }else{
                return $this->sendError('cart is empty.');
            }
        }else{
            return $this->sendError('No item in cart.');
        }
    }

    public function order_payment(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($input, [ 
            'stripe_token_id' => ['required'],
            'address_id' => ['required'],
            'order_id' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        $user = Auth::user();
        if (!UserAddresses::where(['user_id'=> $user->id,'id'=> $input['address_id']])->exists()) {
            return $this->sendError('invalid address.');
        }

        if (!Order::where(['user_id'=> $user->id,'id'=> $input['order_id']])->exists()) {
            return $this->sendError('invalid order.');
        }

        $stripe = new \Stripe\StripeClient(
            'sk_test_51JO3CvSC1rawird4B0azBqbnNNf3rEObdqUid4U6vDSBr7uDJBEtRWon02u2PYNGsdEBvDIRIpFt49Q8DHapvUlp00Ze0Tb8OW'
        );

        #$stripe = new \Stripe\StripeClient(
        #    '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'
        #); //live account

        try {
            $stripe->tokens->retrieve(
                $input['stripe_token_id'],
                []
            );
        } catch(\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            return $this->sendError($e->getMessage());
            echo 'Status is:' . $e->getHttpStatus() . '\n';
            echo 'Type is:' . $e->getError()->type . '\n';
            echo 'Code is:' . $e->getError()->code . '\n';
            // param is '' in this case
            echo 'Param is:' . $e->getError()->param . '\n';
            echo 'Message is:' . $e->getError()->message . '\n';
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $this->sendError($e->getMessage());
            // Too many requests made to the API too quickly
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this->sendError($e->getMessage());
         // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $this->sendError($e->getMessage());
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $this->sendError($e->getMessage());
            // Network communication with Stripe failed
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->sendError($e->getMessage());
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
            // Something else happened, completely unrelated to Stripe
        }

        $address = UserAddresses::where(['user_id'=> $user->id,'id'=> $input['address_id']])->first();
        $order = Order::where(['user_id'=> $user->id,'id'=> $input['order_id']])->first();

        try {
            // Use Stripe's library to make requests...
            $charges  = $stripe->charges->create([
                "amount" => (int)$order->total,
                "currency" => "usd",
                "source" => $input['stripe_token_id'], // obtained with Stripe.js
                'description' => 'Stillman app order information',
                "metadata" => ["order_id" => $input['order_id']],
                "receipt_email" => $address->email,
                'shipping' => [
                    'name' => $address->name,
                    'address' => [
                      'line1' => $address->addressline1,
                      'city' => 'San Francisco',
                      'state' => 'CA',
                      'country' => 'US',
                      /* 'city' => $address->name,
                      'state' =>$address->name,
                      'country' => $address->name, */
                      'postal_code' => $address->pincode,
                    ],
                  ],
              ]);
        } catch(\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            return $this->sendError($e->getMessage());
            echo 'Status is:' . $e->getHttpStatus() . '\n';
            echo 'Type is:' . $e->getError()->type . '\n';
            echo 'Code is:' . $e->getError()->code . '\n';
            // param is '' in this case
            echo 'Param is:' . $e->getError()->param . '\n';
            echo 'Message is:' . $e->getError()->message . '\n';
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $this->sendError($e->getMessage());
            // Too many requests made to the API too quickly
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this->sendError($e->getMessage());
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $this->sendError($e->getMessage());
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $this->sendError($e->getMessage());
            // Network communication with Stripe failed
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->sendError($e->getMessage());
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
            // Something else happened, completely unrelated to Stripe
        }

        if($charges){
            /* echo "<pre>";print_r($charges);die;*/
            $balance_transaction = $charges->balance_transaction;
            Order::where(['user_id'=> $user->id,'id'=> $input['order_id']])->update(['transaction_id' => $balance_transaction,'payment_status'=>'completed','address_id'=>$input['address_id']]);

            Carts::where(['user_id'=> $user->id,'order_id'=> $input['order_id']])->update(['is_order_confirmed' => '1']);
            
            $getItems = Carts::where(['user_id'=> $user->id,'order_id'=> $input['order_id']])->get();
            foreach($getItems as $item){
                Notification::create(['user_id'=>$user->id,'order_id'=>$input['order_id'],'cart_id'=>$item->id,'product_id'=>$item->product_id,'shipment_status'=>1]);
            }

            $success['transaction'] = $balance_transaction;
            $success['order_id'] = $input['order_id'];
            return $this->sendResponse($success, 'Payment has been confirmed successfully.');
        }else{
            return $this->sendError('Something wrong please try again.');
        }
    }

    public function single_order(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($input, [ 
            'product_id' => ['required'],
            'quantity' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        $user = Auth::user();
        if (Products::where('id', $input['product_id'])->exists()) {
            $product = Products::where('id', $input['product_id'])->first();

            DB::table('carts')->where(['user_id'=>$user->id,'is_order_confirmed'=>0,'is_single_cart'=>1])->delete();
            
            $input['is_single_cart'] = 1;
            $input['user_id'] = $user->id;
            $cart = Carts::create($input);
            if($cart){
                $data = app('App\Http\Controllers\API\UserController')->common_get_single_cart($user);
                if(is_array($data)){
                    $data['message'] = "Product has been add cart successfully.";
                    return response()->json($data, 200);
                }else{
                    return $this->sendError('cart is empty.');
                }
            }else{
                return $this->sendError('Something wrong please try again.');
            }

        }else{
            return $this->sendError('Invalid product id');
        }
    }

    public function get_orders_list(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [ 
            'type' => [
                'required',
                Rule::in(['0','1','2']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());      
        }
        
        $user = Auth::user();

        if($input['type'] == 1){
            $order_type = 'completed';
        }else{
            $order_type = 'pending';
        }

        $orders = Order::select('id','service_fee','service_amount','tax','tax_amount','sub_total','total','created_at')->where(['user_id'=> $user->id,'payment_status'=>$order_type])->get();
        if(!$orders->isEmpty()){
            $product_path = URL::to('/uploads/product_imgs');
            foreach($orders as $order){
                $carts = Carts::select('id','product_id','quantity')->where(['user_id'=> $user->id,'order_id'=>$order->id])->get();
                if(!$carts->isEmpty()){
                    foreach($carts as $cart){
                        $product = DB::table('products')->selectRaw('*')->where('id', $cart->product_id)->first();
                        if($product){
                            $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $cart->product_id)->first();
                            $cart->name = $product->name;
                            $cart->price = $product->price;
                            $cart->stock = $product->stock;
                            if($images){
                                $cart->image = $images->image;
                                $cart->height = $images->height;
                                $cart->width = $images->width;
                            }
                        }
                    }
                    $order->carts = $carts;
                    //echo "<pre>";print_r($carts);die;
                }else{
                    $order->carts = (object)[];
                }
            }

            $data['message'] = "Order list has been get successfully.";
            $data['order'] = $orders;

            return response()->json($data, 200);
            echo "<pre>";print_r($orders);die;
        }else{
            return $this->sendError('No order found');
        }
    }

    public function get_orders(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [ 
            'type' => [
                'required',
                Rule::in(['0','1','2']),
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());      
        }
        
        $user = Auth::user();

        if($input['type'] == 0){
            $order_type = 'pending';
            $items = DB::table('carts AS c')
                    ->rightJoin('orders AS o', 'c.order_id', '=', 'o.id')
                    ->select('c.id','c.product_id','c.quantity','c.user_id','c.shipment_status', 'o.id as order_id','o.service_amount','o.service_fee','o.tax_amount','o.tax','o.sub_total','o.total','o.payment_status','o.is_pickup','o.address_id','o.created_at as order_date','c.updated_at as orderstatusdate')
                    ->where('o.payment_status', $order_type)
                    ->where('c.user_id', $user->id)
                    ->orderBy('c.id', 'DESC')
                    ->paginate(20);
        }else if($input['type'] == 1){
            $order_type = 'completed';
            $items = DB::table('carts AS c')
                    ->rightJoin('orders AS o', 'c.order_id', '=', 'o.id')
                    ->select('c.id','c.product_id','c.quantity','c.shipment_status','c.user_id', 'o.id as order_id','o.service_amount','o.service_fee','o.tax_amount','o.tax','o.sub_total','o.total','o.payment_status','o.is_pickup','o.pickup_date','o.pickup_time','o.address_id','o.created_at as order_date','c.updated_at as orderstatusdate')
                    ->where('o.payment_status', $order_type)
                    ->where('c.user_id', $user->id)
                    ->orderBy('c.id', 'DESC')
                    ->paginate(20);
        }else if($input['type'] == 2){
            $items = DB::table('carts AS c')
                    ->rightJoin('orders AS o', 'c.order_id', '=', 'o.id')
                    ->select('c.id','c.product_id','c.quantity','c.shipment_status','c.user_id', 'o.id as order_id','o.service_amount','o.service_fee','o.tax_amount','o.tax','o.sub_total','o.total','o.payment_status','o.is_pickup','o.pickup_date','o.pickup_time','o.address_id','o.created_at as order_date','c.updated_at as orderstatusdate')
                    ->where('c.user_id', $user->id)
                    ->orderBy('c.id', 'DESC')
                    ->paginate(20);
        }

        //echo "<pre>";print_r($items);die;
        if(!$items->isEmpty()){
            $product_path = URL::to('/uploads/product_imgs');
            
            
            foreach($items as $item){
                $orderlist = Carts::where(['order_id'=> $item->order_id,'user_id'=> $user->id])->get();
                $orderCount = $orderlist->count();
                //echo $item->order_id;die;
                //echo $item->address_id;die;
                $item->order_count = $orderCount;
                if($item->is_pickup == 1){
                    if($item->shipment_status==0){
                        $item->shipment_status_msg = 'Cancelled';
                    }else{
                        $item->shipment_status_msg = 'Picked up';
                    }
                }else{
                    $item->shipment_status_msg = $this->shipment_status($item->shipment_status);
                }
                $product = DB::table('products')->selectRaw('*')->where('id', $item->product_id)->first();
                if($product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $item->product_id)->first();
                    $item->name = $product->name;
                    $item->price = $product->price;
                    $item->stock = $product->stock;
                    if($images){
                        $item->image = $images->image;
                        $item->height = $images->height;
                        $item->width = $images->width;
                    }
                }

                $address = UserAddresses::where(['user_id'=> $user->id,'id'=> $item->address_id])->first();
                if(!empty($address)){
                    $item->address = $address;
                }else{
                    $item->address = (object)[];
                }
                $item->expectingDeliveryDate = Carbon::now()->addDays(7);
            }

            $custom = collect(['success' => '1','message' => 'Order item list has been get successfully.']);
            $data = $custom->merge($items);
            return response()->json($data, 200);
        }else{
            return $this->sendError('No order found');
        }
    }

    public function get_order_details(Request $request,$id)
    {
        $user = Auth::user();
        //echo $user->id;die;
        if (Carts::where(['id'=> $id,'user_id'=>$user->id])->exists()) {
            $item = DB::table('carts AS c')
                    ->rightJoin('orders AS o', 'c.order_id', '=', 'o.id')
                    ->select('c.id','c.product_id','c.quantity','c.shipment_status','c.user_id', 'o.id as order_id','o.service_amount','o.service_fee','o.tax_amount','o.tax','o.sub_total','o.total','o.is_pickup','o.pickup_date','o.pickup_time','o.is_pickup','o.payment_status','o.address_id','o.created_at as order_date','c.updated_at as orderstatusdate')
                    ->where('c.user_id', $user->id)
                    ->where('c.id', $id)
                    ->first();

            if(!empty($item)){
                $product_path = URL::to('/uploads/product_imgs');
                $orderlist = Carts::where(['order_id'=> $item->order_id,'user_id'=> $user->id])->get();
                $orderCount = $orderlist->count();
                
                if($item->is_pickup == 1){
                    $item->shipment_status_msg = 'Picked up';
                    /* if($item->shipment_status==0){
                        $item->shipment_status_msg = 'Cancelled';
                    }else{
                        $item->shipment_status_msg = 'Picked up';
                    } */
                    $item->pickup_date = Carbon::createFromFormat('Y-m-d', $item->pickup_date)->format('m/d/Y');
                    //Carbon::createFromFormat('Y-m-d', $item->pickup_date)->format('m/d/Y');
                    $item->pickup_time = $item->pickup_time;
                }else{
                    $item->shipment_status_msg = $this->shipment_status($item->shipment_status);
                    $item->pickup_date = '';
                }
                //echo $item->address_id;die;
                $item->order_count = $orderCount;
                $product = DB::table('products')->selectRaw('*')->where('id', $item->product_id)->first();
                if($product){
                    $images = DB::table('product_images')->selectRaw('*,CONCAT("'.$product_path.'" "/", image) as image')->where('product_id', $item->product_id)->first();
                    $item->name = $product->name;
                    $item->price = $product->price;
                    $item->stock = $product->stock;
                    $item->size_of_item = $product->size_of_item;
                    if($images){
                        $item->image = $images->image;
                        $item->height = $images->height;
                        $item->width = $images->width;
                    }
                }
    
                $address = UserAddresses::where(['user_id'=> $user->id,'id'=> $item->address_id])->first();
                if(!empty($address)){
                    $item->address = $address;
                }else{
                    $item->address = (object)[];
                }

                $item->expectingDeliveryDate = Carbon::now()->addDays(7);

                $data['message'] = "Order details has been get successfully.";
                $data['order'] = $item;
                return response()->json($data, 200);
            }
        }else{
            return $this->sendError('No order found');
        }
    }

    public function shipment_status($id)
    {
        if($id == 0){
            return 'Cancelled';
        }else if($id == 1){
            return 'Processing Order';
        }else if($id == 2){
            return 'Delivered';
        }else if($id == 3){
            return 'Pending (due to high traffic)';
        }else if($id == 4){
            return 'Refund in progress';
        }else if($id == 5){
            return 'Refund Completed';
        }else if($id == 6){
            return 'Picked up';
        }else{
            return '';
        }
    }

    public function cancel_order(Request $request)
    {
        $input = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [ 
            'cart_id' => ['required'],
            'order_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());      
        }
        
        $user = Auth::user();
        $cart = Carts::where(['user_id'=> $user->id,'id'=>$input['cart_id'],'order_id'=>$input['order_id']])->first();
        if(!empty($cart)){
            if($cart->shipment_status == 0){
                return $this->sendError('this order is already cancelled.');
            }

            $product = Products::where('id', $cart->product_id)->first();
            if($product){
                $tem_name = $product->name;
            }else{
                $tem_name = '';
            }

            //$time = Carbon::now("UTC");
            //$updated_at = Carbon::now();
            //echo $time.' '.$updated_at;die;

            $response = Carts::where(['id'=>$input['cart_id']])->update(['shipment_status' => 0]);
            if($response){
                $getcart = Carts::select('id','updated_at')->where(['id'=>$input['cart_id']])->first();
                $message = 'Your order with item: '.$tem_name.' has been cancelled and refund will process for that.';
                $details = [
                    'title' => 'Mail from stillmanapp.com',
                    'body' => $message
                ];
            
                Mail::to($user->email)->send(new \App\Mail\MyTestMail($details));

                $success['shipment_status'] = '0';
                $success['shipment_status_msg'] = 'Cancelled';
                //$success['orderstatusdatee'] = Carbon::now()->format('Y-m-d H:m:s');
                //$success['orderstatusdate'] = $getcart->updated_at;
                $success['orderstatusdate'] = date('Y-m-d H:i:s', strtotime($getcart->updated_at));

                return $this->sendResponse($success, 'Order has been cancel successfully.');
            }else{
                return $this->sendError('Something wrong please try again.');
            }
        }else{
            return $this->sendError('No order found');
        }
    }
    
}