<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Order;
use App\Models\Redeem;
use App\Models\Advertisement;
use App\Models\MembershipPlan;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use Mail;
use App\Mail\SendOtp;
use File;
use App\Http\Controllers\Admin\SubscriptionController;
use DateTime;

class ApiController extends Controller
{
    
    public  $successStatus = 200,
			$errorStatus   = 401,
			$pagenotfound  = 404,
			$failedStatus  = 500;

  

    public function updateCustomer(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|integer|exists:users,id',
            'name'     => 'required|string|max:191',
            'address'  => 'required|string',
            'dob'      => 'required',
            'gender'   => 'required',
        ]);

        if($validator->fails()) {
            $errorresponse = [
                'status'  => $this->failedStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $check = User::where('id', request('user_id'))->where('role_id',2)->count();
        if($check == 1)
        {   
            $data = [
            'name'       => request('name') ?: NULL,
            'address'    => request('address') ? : NULL,
            'dob'        => date('Y-m-d',strtotime(request('dob'))) ? : NULL,
            'gender'     => request('gender') ? : NULL,
            'status'     => 'AC',
            ];
            $is_update = User::where('id', request('user_id'))->update($data);
            if($is_update)
            {
                $response = [
                'message' => 'Update Success',
                'status' => $this->successStatus,
                'response' => 'success',
                ];
                return response()->json($response, $this->successStatus);
            }
            else {
                $response = [
                    'message' => 'Update faild',
                    'status'  => $this->failedStatus,
                    'response' => 'failed',
                ];
                return response()->json($response, $this->errorStatus);
            }
        }
        else
        {
            $response = [
                'message' => 'Invalid user id',
                'status'  => $this->failedStatus,
                'response' => 'failed',
            ];
            return response()->json($response, $this->errorStatus);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required',
            'old_password' => 'required|string|max:191',
            'new_password' => 'required|string|max:191',
        ]);

        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
       
        $user_id = request('user_id');
        $old_password = request('old_password');
        $new_password = request('new_password');
        
        //Check user login detail
        $user = User::where('id',$user_id)->whereIn('users.role_id', [2,3])->first();
        if($user)
        {
            
            if(Hash::check($old_password, $user->password))
            {
               $user->password = Hash::make($new_password);
               $is_update = $user->save();
               if($is_update)
               {
                $response = [
                'message' => 'password has been updated.',
                'status' => $this->successStatus,
                'response' => 'success',
                ];
                return response()->json($response, $this->successStatus);
               }
               else
               {
                $response = [
                    'message' => 'Update faild',
                    'status'  => $this->failedStatus,
                    'response' => 'failed',
                ];
                return response()->json($response, $this->failedStatus);
               }
            }
            else
            {
                $response = [
                    'message' => 'Password does not match',
                    'status'  => $this->failedStatus,
                    'response' => 'failed',
                ];
                return response()->json($response, $this->failedStatus);
            }
        }
        else
        {
            $response = [
                    'message' => 'User not found',
                    'status'  => $this->failedStatus,
                    'response' => 'failed',
                ];
                return response()->json($response, $this->failedStatus);
        }
    }

   

 
  
   

    /**
     * Get Coupons
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getCoupons(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'business_id'    => 'required|exists:users,id',
        ]);

        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $check = DB::table('orders')
        ->where('status','AC')    
        ->where('user_id',request('business_id'))
        ->count();
        $data = array();
        $time = array();
        $business_details = array();

        if($check > 0)
        {
            $coupons = DB::table('users as u')
            ->join('user_details as ud','ud.user_id','=','u.id')
            ->join('addresses as ad','ad.user_id','=','u.id')
            ->join('orders as o','o.user_id','=','u.id')
            ->join('coupons as c','o.coupon_id','=','c.id')
            ->join('categories as cat','ud.category','=','cat.id')
            ->where('u.id',request('business_id'))
            ->where('o.status','AC')
            ->select('u.id as business_id','ud.business_name','ud.first_name','ud.last_name','ud.main_contact','ud.woman_owned','ud.veteran_owned','ad.address','ad.city','ad.state','ad.country','ud.mobile_number','ud.website_link','ud.business_category','c.id as coupon_id','c.title as coupon_title','c.description as coupon_details','o.coupon_image','o.remaining_coupon','cat.id as category_id','cat.name as category_name')
            ->get();

            $time = DB::table('business_timings as tm')
            ->join('days as dy','tm.day_id','=','dy.id')
            ->select('dy.name as day','tm.opening_time','tm.closing_time','tm.status')
            ->where('tm.user_id',request('business_id'))
            ->orderBy('dy.id','asc')
            ->get();

            $business_category = Category::find($coupons[0]->business_category);

            foreach ($coupons as $coupon) {
                if(empty($coupon->coupon_image))
                {
                    $img = NULL;
                }else{
                    $img = asset('images').'/'.$coupon->coupon_image;
                }
                $data[] = array(
               
                'coupon_id'         => $coupon->coupon_id ?? '',
                'coupon_title'      => $coupon->coupon_title ?? '',
                'coupon_details'    => $coupon->coupon_details ?? '',
                'coupon_image'      => $img,
                'remaining_coupon'  => $coupon->remaining_coupon ?? '',
               
                );
                $business_details = array(
                    'business_id'       => $coupon->business_id ?? '',
                    'business_name'     => $coupon->business_name ?? '',
                    'first_name'        => $coupon->first_name ?? '',
                    'last_name'         => $coupon->last_name ?? '',
                    'woman_owned'       => $coupon->woman_owned ?? '',
                    'veteran_owned'     => $coupon->veteran_owned ?? '',
                    'address'           => $coupon->address.' '.$coupon->city.' '.$coupon->state.' '.$coupon->country ?? '',
                    'mobile_number'     => $coupon->mobile_number ?? '',
                    'main_contact'      => $coupon->main_contact ?? '',
                    'website_link'      => $coupon->website_link ?? '',
                    'business_category_id' => $business_category->id ?? '',
                    'business_category' => $business_category->name ?? '',
                    'category_id'       => $coupon->category_id ?? '',
                    'category_name'     => $coupon->category_name ?? '',
                );
            }

            $response = [
                'message'       => 'Coupons details',
                'status'        => $this->successStatus,
                'response'      => 'success',
                'coupons_data'  => $data,
                'shop_time'     => $time,
                'business_detail' => $business_details,
            ];

        }
        else
        {
            $response = [
                'message'  => 'Coupons details not found',
                'status'   => $this->successStatus,
                'response' => 'success',
                'coupons_data' => $data,
            ];
        }
        return response()->json($response, $this->successStatus);
    }


    public function getCouponsStatus(Request $request) 
    {
         $validator = Validator::make($request->all(), [
            'business_id'    => 'required|exists:users,id',
        ]);

        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }

        $data = DB::table('users as u')
        ->join('orders as o','o.user_id','=','u.id')
        ->join('coupons as c','o.coupon_id','=','c.id')
        ->select('u.id as business_id','c.id as coupon_id','c.no_of_coupons as total_coupon','o.remaining_coupon')
        ->where('o.status','AC')
        ->where('u.id',request('business_id'))
        ->get();

        $response = [
            'message' => 'Coupons details',
            'status'  => $this->successStatus,
            'response' => 'success',
            'coupons_data'    => $data,
        ];
        return response()->json($response, $this->successStatus);
    }


    /**
     * Get membership plan
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getMembershipPlan() 
    {
        $data = array();
        $output = array();
        $coupon_data = array();
        $plans = DB::table('membership_plans')
        ->where('status','AC')
        ->whereNull('deleted_at')
        ->get();

        foreach($plans as $value)
        {
            $data['plan_id'] = $value->id;            
            $data['plan_name'] = $value->name;            
            $data['price'] = $value->price;            
            $coupons = DB::table('coupons')->where('plan_id',$value->id)->select('id','title','coupon_amount','no_of_coupons')->get();

            foreach ($coupons as $coupon) {
                $coupon_data[] = array(
                      'coupon_id' =>   $coupon->id,
                      'coupon_name' =>   $coupon->title,
                      'coupon_amount' =>   $coupon->coupon_amount,
                      'no_of_coupons' =>   $coupon->no_of_coupons
                );
                
            }
            $data['plan_type'] =  $coupon_data;
        
            $coupon_data = [];
            
            array_push($output, $data);
            $data = []; 
        }


        $response = [
            'message' => 'Plan details',
            'status'  => $this->successStatus,
            'response' => 'success',
            'plans'    => $output,
        ];
        return response()->json($response, $this->successStatus);
    }

     /**
     * Get membership plan status
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getPlanStatus(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'business_id'    => 'required|exists:users,id',
        ]);

        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $is_business = User::where('role_id',3)->where('id',request('business_id'))->count();
        if($is_business == 1)
        {
            $data    = array();
            $output  = array();
            $all_plans  = array();

            $is_exist_active_order = Order::where('user_id',request('business_id'))
            ->where('status','AC')            
            ->count();
          
            if($is_exist_active_order == 1)
            {
                $sql = DB::table('orders as o')
                ->join('membership_plans as plan','plan.id','=','o.plan_id')
                ->join('coupons as c','o.coupon_id','=','c.id')
                ->select('plan.id as plan_id','plan.name as plan_name','plan.price','c.id as coupon_id','c.no_of_coupons as total_coupon','o.remaining_coupon','o.purchase_date','o.expiry_date')
                ->where('o.user_id',request('business_id'))
                ->where('o.status','AC')
                ->first();
               
                $subscript_start = new Carbon($sql->purchase_date);
                $subscript_end = new Carbon($sql->expiry_date);
                $data = array(
                    'plan_id'           => $sql->plan_id  ?? '',
                    'plan_name'         => $sql->plan_name  ?? '',
                    'price'             => $sql->price  ?? '',
                    'coupon_id'         => $sql->coupon_id  ?? '',
                    'total_coupon'      => $sql->total_coupon  ?? '',
                    'remaining_coupon'  => $sql->remaining_coupon  ?? '',
                    'purchase_date'     => $subscript_start->diffForHumans()  ?? '',
                    'expiry_date'       => $subscript_end->diffForHumans()  ?? '',
                    'status'            => 'Active',
                );

                $remaning_plans = DB::table('membership_plans')
                ->where('status','AC')
                ->whereNotIn('id',[$sql->plan_id])
                ->get();
            }else {
                $data = array(
                    'message' => 'There is no active plan.',
                    'status'            => 'Inactive',
                );
                $remaning_plans = DB::table('membership_plans')
                ->where('status','AC')
                ->get();
            }

            //------get remaning plans---
                       
            foreach($remaning_plans as $value)
            {
                $all_plans['plan_id'] = $value->id ?? '';            
                $all_plans['plan_name'] = $value->name ?? '';            
                $all_plans['price'] = $value->price ?? '';            
                $coupons = DB::table('coupons')
                ->where('plan_id',$value->id)
                ->where('status','AC')
                ->select('id','title','description','coupon_amount','no_of_coupons')->get();
                if(count($coupons) <= 0)
                {
                    continue;
                }
                foreach ($coupons as $coupon) {
                    $coupon_data = array(
                          'coupon_id'      =>   $coupon->id ?? '',
                          'coupon_name'    =>   $coupon->title ?? '',
                          'coupon_details' =>   $coupon->description ?? '',
                          'coupon_amount'  =>   $coupon->coupon_amount ?? '',
                          'no_of_coupons'  =>   $coupon->no_of_coupons ?? '',
                    );
                    $all_plans['its_coupons'][] = $coupon_data;
                    $coupon_data =[];
                }
                array_push($output, $all_plans);
                $all_plans = []; 
            }
            $response = [
                'message' => 'Plan Details',
                'status'  => $this->successStatus,
                'response' => 'success',
                'plan_status' => $data,
                'remaning_plans' => $output,
            ];
            return response()->json($response, $this->successStatus);
        }
        else{
            $response = [
            'message' => 'Is not a business account',
            'status'  => $this->errorStatus,
            'response' => 'faild',
            ];
            return response()->json($response, $this->errorStatus);
        }
    }


    /**
     * Forget Password
     *
     * @return \Illuminate\Http\Response
     */
    
    public function forgetPassword(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email'  => 'required|email|exists:users,email',
        ]);

        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $getUser = User::where('email',request('email'))->first();
        $otp = rand(1111,9999);
        $email =  $request->get('email');
        $data = array(

            'otp' => $otp ?? '', 
            'message' => 'use this otp to reset your password', 
        );
        try{
            $send =  Mail::to($email)->send(new SendOtp($data));
            $response = [
            'message' => 'otp sent Successfully',
            'status'  => $this->successStatus,
            'response' => 'success',
            'otp'       => $otp,
            'user_id' => $getUser->id ?? ''
            ];
            return response()->json($response, $this->successStatus);
        }
        catch (\Exception $e) { 

            $response = [
            'message' => 'Oops! something went wrong.',
            'status'  => $this->errorStatus,
            'response' => 'faild',
            ];
            return response()->json($response, $this->errorStatus);
        }    
    }


    /**
     * Update Password
     *
     * @return \Illuminate\Http\Response
     */
    
    public function updatePassword(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|exists:users,id',
            'password'  => 'required|confirmed|min:6',
            'password_confirmation'  => 'required|min:6',
        ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }

        $user = User::find(request('id'));

        $is_update = $user->update(['password'=>Hash::make(request('password'))]);
        if($is_update)
        {
            $response = [
            'message' => 'Password has been updated successfully.',
            'status'  => $this->successStatus,
            'response' => 'success',
           ];
            return response()->json($response, $this->successStatus);
        }
        else{
            $response = [
            'message' => 'Something went wrong.',
            'status'  => $this->errorStatus,
            'response' => 'failed',
            ];
            return response()->json($response, $this->errorStatus);
        }
    }

    
    /**
     * Search Api
     * @param $location, $search_strig is string
     *  $category_id, $sub_category_id,$user_id is int
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function search(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'user_id'           => 'nullable|exists:users,id',
            'category_id'       => 'nullable|exists:categories,id',
            'subcategory_id'    => 'nullable|exists:categories,id',
            'location'          => 'nullable|max:500|string',
            'search_tag'        => 'nullable|max:500|string',
            'business_category' => 'nullable',
            ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }

      
        $query = DB::table('users as u')
        ->join('user_details as ud','u.id','=','ud.user_id')
        ->join('addresses as ad','u.id','=','ad.user_id')
        ->join('categories as cat','ud.category','=','cat.id');
       
        if(!empty(request('business_category')))
        {
            $query = $query->where('ud.business_category',request('business_category'));
        }
        if(!empty(request('category_id')))
        {
            $query = $query->Where('cat.id',request('category_id'));
        }
        if(!empty(request('category_id')) && !empty(request('subcategory_id')))
        {
            $query = $query->Where('cat.id',request('subcategory_id'))->orWhere('cat.parent_id',request('category_id'));
        }
        if(!empty(request('location')))
        {
            $location = request('location');
            $query = $query->Where('ad.address', 'LIKE', "%{$location}")
            ->orWhere('ad.city', 'LIKE', "%{$location}");
        }
        if(!empty(request('search_tag')))
        {
            $search_tag = request('search_tag');
            $query = $query->orWhere('ad.address', 'LIKE', "%{$search_tag}%")
            ->orWhere('ud.business_name', 'LIKE', "%{$search_tag}%")
            ->orWhere('cat.name', 'LIKE', "%{$search_tag}%");
        }
        $query->select('u.id as business_id','ud.business_name','cat.name as category_name','ud.category as category_id','ud.sub_category as subcategory_id','ud.business_category','ad.address','ad.city','ad.state','ud.image','ud.mobile_number','ud.website_link','u.email');
        $data  = $query->get();

        $output =array();
        foreach ($data as $value) {
            if (empty($value->image)) {
                $img = NULL;
            }else {
                $img = asset('images').'/'.$value->image;
            }
            $time = DB::table('business_timings as tm')
            ->join('days as dy','tm.day_id','=','dy.id')
            ->where('tm.user_id',$value->business_id)
            ->select('tm.id','dy.name as day','tm.opening_time','tm.closing_time','tm.status')
            ->get();

            $business = array(
                  'business_id'      =>   $value->business_id ?? '',
                  'business_name'    =>   $value->business_name ?? '',
                  'address'          =>   $value->address.' '. $value->city.' '. $value->state ?? '',
                  'category_id'      =>   $value->category_id ?? '',
                  'subcategory_id'   =>   $value->subcategory_id ?? '',
                  'business_category'=>   $value->business_category ?? '',
                  'category_name'    =>   $value->category_name ?? '',
                  'image'            =>   $img,
                  'opning_time'      =>   $value->opning_time ?? '',
                  'closing_time'     =>   $value->closing_time ?? '',
                  'mobile_number'    =>   $value->mobile_number ?? '',
                  'website_link'     =>   $value->website_link ?? '',
                  'email'            =>   $value->email ?? '',
                  'shop_timing'      =>   $time ?? '',
            );
            array_push($output,$business);
        }
     
        $response = [
            'message'  => 'Search Reasult',
            'status'   => $this->successStatus,
            'response' => 'success',
            'result'   => $output
            
            ];
            return response()->json($response, $this->successStatus);
    }

    /**
     * remove or update progile image for vendor and customer
     * @param int user_id, status string(update/remove) 
     *  image is profile photo
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function changeProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'user_id'        => 'required|exists:users,id',
        'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'status'         => 'required|string|max:10',
        ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $satus  = request('status');
        $userId = request('user_id');

        $user = User::find($userId);
        
        $oldProfile = $user->image;
        if($satus === 'remove')
        {
            if(File::exists(public_path('images/'.$user->image)))
            {
              File::delete(public_path('images/'.$user->image));
            }
            $user->image = NULL;
            $user->save();
            $response = [
                'message' => 'Profile has been remove successfully.',
                'status'  => $this->successStatus,
                'response' => 'success',
               ];
            return response()->json($response, $this->successStatus);
        }
        elseif($satus === 'update')
        {
            if($request->hasFile('image'))
            {
                $imageName  = time().'.'.$request->image->extension();  
                $is_upload  = $request->image->move(public_path('images'), $imageName);
                if($is_upload)
                {
                    UserDetails::where('user_id',$userId)
                    ->update(['image'=>$imageName]);

                    if(File::exists(public_path('images/'.$oldProfile)))
                    {
                     File::delete(public_path('images/'.$oldProfile));
                    }
                    $response = [
                        'message' => 'Profile has been uploaded successfully.',
                        'status'  => $this->successStatus,
                        'response' => 'success',
                       ];
                    return response()->json($response, $this->successStatus);
                }
                else
                {
                    $response = [
                        'message' => 'Upload failed.',
                        'status'  => $this->errorStatus,
                        'response' => 'success',
                       ];
                    return response()->json($response, $this->errorStatus);
                }
               
            }
            else {
                $response = [
                    'message' => 'Invalid Image.',
                    'status'  => $this->errorStatus,
                    'response' => 'success',
                   ];
                return response()->json($response, $this->errorStatus);
            }
        }
    }

    /**
     * remove or update progile image for vendor and customer
     * @param int user_id, status string(update/remove) 
     *  image is profile photo
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function grabCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'user_id'        => 'required|exists:users,id',
        'business_id'    => 'required|exists:users,id',
        'coupon_id'    => 'required|exists:coupons,id',
        ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $user_id = $request->get('user_id');
        $coupon_id = $request->get('coupon_id');
        $business_id = $request->get('business_id');
       
        $isExist = Redeem::where('user_id',$user_id)
        ->where('business_id',$business_id)
        ->where('coupon_id',$coupon_id)
        ->where('status','PN')
        ->count();
        if($isExist > 0)
        {
            $response = [
                'message'  => 'Redeem Request already submited',
                'status'   => $this->successStatus,
                'response' => 'success',
                ];
            return response()->json($response, $this->successStatus);
        }


        $countCheck = Order::where('user_id',$business_id)
        ->where('coupon_id',$coupon_id)
        ->where('status','AC')
        ->count();

        $order = Order::where('user_id',$business_id)
        ->where('coupon_id',$coupon_id)
        ->where('status','AC')
        ->first();

        if($countCheck > 0 && $order->remaining_coupon > 0)
        {
            $data = array(
                'user_id'       => $user_id,
                'coupon_id'     => $coupon_id,
                'business_id'   => $business_id,
                'status'        => 'PN',
                'created_at'    => date('Y-m-d H:i:s',time()),
            );
            $is_save = Redeem::insert($data);
            if($is_save)
            {
                $response = [
                    'message'  => 'Redeem Request submited',
                    'status'   => $this->successStatus,
                    'response' => 'success',
                    ];
                return response()->json($response, $this->successStatus);
            }
            else{
                $errorresponse = [
                    'status'   => $this->errorStatus,
                    'message'  =>  'Request not submited. Try later',
                    'response' => 'failed',
                ];
                return response()->json($errorresponse, $this->errorStatus);
            }
            
        }
        else
        {
            $errorresponse = [
                'status'   => $this->errorStatus,
                'message'  =>  'Coupon not available.',
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }

    }

    
    /**
     * get list of redeem request for vendor
     * @param int business_id
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function redeemRequestVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:users,id',
        ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $business_id = $request->get('business_id');
        $users = DB::table('redeems as rd')
        ->join('users as u','rd.user_id','=','u.id')
        ->join('user_details as ud','ud.user_id','=','u.id')
        ->join('addresses as ad','ad.user_id','=','u.id')
        ->where('rd.business_id',$business_id)
        ->orderBy('rd.created_at','desc')
        ->select('u.*','u.id as customer_id','ud.*','ad.*','rd.status as request_status','rd.id as request_id')
        ->get();
        $data = array();
  
        if(count($users)>0)
        {
            foreach ($users as  $user) {
                if (empty($user->image)) {
                    $img = NULL;
                }else {
                    $img = asset('images').'/'.$user->image;
                }
                $data[] = array(
                    'redeem_request_id' => $user->request_id ?? '',
                    'customer_id' => $user->customer_id ?? '',
                    'name'        => $user->name.' '.$user->last_name ?? '',
                    'image'       => $img ? : '',
                    'mobile_number' => $user->mobile_number ?? '',
                    'address'     => $user->address.', '.$user->city.', '.$user->state ?? '',
                    'gender'      => $user->gender ?? '',
                    'dob'         => $user->dob ?? '',
                    'email'       => $user->email ?? '',
                    'status'      => $user->request_status ?? '',
                );
            }
            $response = [
                'message'  => 'Redeem Request list',
                'status'   =>  $this->successStatus,
                'response' => 'success',
                'users'    => $data,
                ];
            return response()->json($response, $this->successStatus);
        }
        else
        {
            $response = [
                'message'  => 'Request not found',
                'status'   =>  $this->successStatus,
                'response' => 'success',
                'users'    => $data,
                ];
            return response()->json($response, $this->successStatus);
        }

    }

     /**
     * vendor action on redeem request send by user
     * @param int business_id , user_id, coupon_id
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function actionOnRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id'       => 'required|exists:users,id',
            'user_id'           => 'required|exists:users,id',
            'redeem_request_id' => 'required|exists:redeems,id',
            'status'            =>  'required|string|max:2',
        ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
            exit();
        }
        $getRequest = Redeem::where('id',request('redeem_request_id'))->first();
        $order = Order::where('user_id',request('business_id'))
                ->where('coupon_id',$getRequest->coupon_id)
                ->where('status','AC')
                ->count();
        if($order == 1)
        {
            $status = $request->get('status');
            if($status === 'RJ')
            {
                $is_save = Redeem::where('id',request('redeem_request_id'))
                ->where('user_id',request('user_id'))
                ->update(['status'=>$status]);
                if($is_save)
                {
                    $response = [
                        'message'  => 'Update Successfully',
                        'status'   =>  $this->successStatus,
                        'response' => 'success',
                        ];
                    return response()->json($response, $this->successStatus);
                }
                else
                {
                    $response = [
                        'message'  => 'Update failed!',
                        'status'   =>  $this->errorStatus,
                        'response' => 'fail',
                    ];
                    return response()->json($response, $this->errorStatus);
                }
            }
            else if($status === 'AP')
            {
                $order = Order::where('user_id',request('business_id'))
                ->where('coupon_id',$getRequest->coupon_id)
                ->where('status','AC')
                ->where('remaining_coupon','>=',1)
                ->count();
                if($order >= 1)
                {
                    $order = Order::where('user_id',request('business_id'))
                    ->where('coupon_id',$getRequest->coupon_id)
                    ->where('status','AC')
                    ->where('remaining_coupon','>=',1)
                    ->first();

                    $order->remaining_coupon = $order->remaining_coupon - 1;
                    $order->save();
                    $getRequest->status = "AP";
                    $getRequest->save();
                    $response = [
                        'message'  => 'Update Successfully',
                        'status'   =>  $this->successStatus,
                        'response' => 'success',
                        ];
                    return response()->json($response, $this->successStatus);

                }
                else
                {
                    $order = Order::where('user_id',request('business_id'))
                    ->where('coupon_id',$getRequest->coupon_id)
                    ->where('status','AC')
                    ->first();
                    if($order->remaining_coupon === 0)
                    {
                        
                        $order->status = 'Expired';
                        $order->save();
                        //cancle the subscription on paypal-------
                        //self::cancelSubscription($order->subscription_id);
                        $response = [
                            'message'  => 'Your plan has been expired due to used all coupons',
                            'status'   =>  $this->errorStatus,
                            'response' => 'failed',
                            ];
                        return response()->json($response, $this->errorStatus);

                    }
                    $response = [
                        'message'  => 'Coupon Not available in this plan',
                        'status'   =>  $this->errorStatus,
                        'response' => 'failed',
                        ];
                    return response()->json($response, $this->errorStatus);
                }

                $order = Order::where('user_id',request('business_id'))
                    ->where('coupon_id',$getRequest->coupon_id)
                    ->where('status','AC')
                    ->first();
                if($order->remaining_coupon == 0)
                {
                    $vendor = User::find(request('business_id'));
                    $vendor->status = 'IA';
                    $vendor->save();
                }
            }
            else{
                $response = [
                    'message'  => 'Invalid status',
                    'status'   =>  $this->errorStatus,
                    'response' => 'fail',
                ];
                return response()->json($response, $this->errorStatus);
            }
        }else {
            $response = [
                'message'  => 'Your plan has been expired.',
                'status'   =>  $this->errorStatus,
                'response' => 'failed',
            ];
            return response()->json($response, $this->errorStatus);
        }
    }

    /**
     * get list of requested coupons
     * @param int business_id
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function redeemRequestUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $user_id = $request->get('user_id');
        $businesses = DB::table('redeems as rd')
        ->join('users as u','rd.business_id','=','u.id')
        ->join('user_details as ud','ud.user_id','=','u.id')
        ->join('addresses as ad','ad.user_id','=','u.id')
        ->where('rd.user_id',$user_id)
        ->orderBy('rd.created_at','desc')
        ->select('u.*','ud.*','ad.*','rd.status as request_status','rd.id as request_id')
        ->get();
       
        $data = array();
  
        if(count($businesses)>0)
        {
            foreach ($businesses as  $business) {
                if (empty($business->image)) {
                    $img = NULL;
                }else {
                    $img = asset('images').'/'.$business->image;
                }
                $data[] = array(
                    'redeem_request_id' => $business->request_id ?? '',
                    'business_name'     => $business->business_name ?? '',
                    'image'             => $img,
                    'mobile_number'     => $business->mobile_number ?? '',
                    'address'           => $business->address.', '.$business->city.', '.$business->state ?? '',
                    'email'             => $business->email ?? '',
                    'website_link'      => $business->website_link ?? '',
                    'status'            => $business->request_status ?? '',
                );
            }
         
            $response = [
                'message'  => 'Redeem Request list',
                'status'   =>  $this->successStatus,
                'response' => 'success',
                'users'    => $data,
                ];
            return response()->json($response, $this->successStatus);
        }
        else
        {
            $response = [
                'message'  => 'Request not found',
                'status'   =>  $this->successStatus,
                'response' => 'success',
                'users'    => $data,
                ];
            return response()->json($response, $this->successStatus);
        }

    }

      /**
     * Get Ads list
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getAds() 
    {
        $ads = Advertisement::where('status','AC')
        ->whereDate('end_date','>=',Carbon::today()->toDateString())
        ->get();
        $data = array();
        if(count($ads)>0)
        {
            foreach ($ads as  $add) {
            if(empty($add->image))
            {
                $img = NULL;
            }else{
                $img = asset('images/ads').'/'.$add->image;
            }
            $data[] = array(
                'title' => $add->title ?? '',
                'url'   => $add->url ?? '',
                'image' =>  $img,
            );
           }
           $response = [
               'message'  => 'Ads list',
               'status'   =>  $this->successStatus,
               'response' => 'success',
               'ads_list'    => $data,
               ];
           return response()->json($response, $this->successStatus);
       }
       else
       {
           $response = [
               'message'  => 'Ads not found',
               'status'   =>  $this->successStatus,
               'response' => 'success',
               'ads_list'    => $data,
               ];
           return response()->json($response, $this->successStatus);
       }

    }


    /**
     * Purchase subscription plan
     *
     * @param 
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscriptionPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:users,id',
            'plan_id'     => 'required|exists:membership_plans,id',
            'coupon_id'   => 'required|exists:coupons,id',
        ]);
        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $business = User::find(request('business_id'));
        //----------get plan details-----------
        $plan = MembershipPlan::where('id',request('plan_id'))->where('status','AC')->first();
        $date = new Carbon(now());
        $date = $date->addMinutes(10);
        $date = $date->toIso8601String();
        if($plan)
        {
            $authObj = new SubscriptionController;
            $auth = $authObj->getAuthToken();
            
            $curl = curl_init();
            $url  = "https://api.sandbox.paypal.com/v1/billing/subscriptions";
            $header = array(
                'Authorization:'.$auth->token_type." ".$auth->access_token, 
                'Content-Type:application/json'    
            ); 
    
            $params =
            '{
                "plan_id": "'.$plan->plan_id.'",
                "start_time": "'.$date.'",
               
                "shipping_amount": {
                    "currency_code": "USD",
                    "value": "'.$plan->price.'"
                },
                "subscriber": {
                    "name": {
                    "given_name": "'.$business->business_name.'",
                    "surname": ""
                    },
                    "email_address": "'.$business->email.'",
                    "shipping_address": {
                    "name": {
                        "full_name": "'.$business->business_name.'"
                    },
                    "address": {
                        "address_line_1": "'.$business->address.'",
                        "address_line_2": "Building 17",
                        "admin_area_2": "San Jose",
                        "admin_area_1": "CA",
                        "postal_code": "95131",
                        "country_code": "US"
                    }
                    }
                },
                "application_context": {
                    "brand_name": "Coupon Apps",
                    "locale": "en-US",
                    "shipping_preference": "SET_PROVIDED_ADDRESS",
                    "user_action": "SUBSCRIBE_NOW",
                    "payment_method": {
                    "payer_selected": "PAYPAL",
                    "payee_preferred": "IMMEDIATE_PAYMENT_REQUIRED"
                    },
                    "return_url": "'.route('subscription.activate').'",
                    "cancel_url": "'.route('subscription.cancel.url').'"
                }
            }';
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CERTINFO, 0);
            
            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);    
            if ($error) 
            {
                //echo "cURL Error #:" . $error;
                $response = [
                    'message'  => 'Something went wrong!',
                    'status'   =>  $this->failedStatus,
                    'response' => 'failed',
                    'users'    => $data,
                    ];
                return response()->json($response, $this->failedStatus);
            }
            else
            {
                $coupon = Coupon::find(request('coupon_id'));
                $result = json_decode($response);
                $data = array();
                if(!empty($result->id))
                {
                    $data = array(
                        'subscription_id' => $result->id,
                        'user_id' => $business->id,
                        'plan_id' => $plan->id,
                        'coupon_id' => request('coupon_id'),
                        'remaining_coupon' => $coupon->no_of_coupons,
                        'purchase_date' => date('Y-m-d',strtotime($date)),
                        'expiry_date' =>  date('Y-m-d', strtotime("+3 months", strtotime($date))),
                        'status' => $result->status,
                    );

                    $is_save = Order::insert($data);
                    if($is_save)
                    {
                        $data = array(
                            'url' => $result->links[0]->href, 
                        );
                        $response = [
                            'message'   => 'Subscription created, payment is panding',
                            'status'    =>  $this->successStatus,
                            'response'  => 'success',
                            'payment_url' => $data,
                        ];
                        return response()->json($response, $this->successStatus);
                    }
                }else {
                    $response = [
                        'message'   => 'Subscription failed',
                        'status'    =>  $this->errorStatus,
                        'response'  => 'failed',
                        'payment_url' => $data,
                    ];
                    return response()->json($response, $this->errorStatus);
                }
            }
        }
    }


    // public function showSubscription()
    // {
        
    //     $id = request('id');
       
    //     $authObj = new SubscriptionController;
    //     $auth = $authObj->getAuthToken();
        
    //     $curl = curl_init();
    //     $url  = "https://api.sandbox.paypal.com/v1/billing/subscriptions/".$id;
    
    //     $header = array(
    //         'Authorization:'.$auth->token_type." ".$auth->access_token, 
    //         'Content-Type:application/json'    
    //     ); 
    //     curl_setopt($curl, CURLOPT_URL, $url);
    //     curl_setopt($curl, CURLOPT_HEADER, false);
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($curl, CURLOPT_POST, false);     
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl, CURLOPT_CERTINFO, 0);
        
    //     $response = curl_exec($curl);
    //     $error = curl_error($curl);
    //     curl_close($curl);  
    //     $result = json_decode($response);
    //     dd($result,  $error);

    // }

    public function activateSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_id' =>   'required',
            'ba_token'        =>   'required',
            'token'           =>   'required',
        ]);

        if($validator->fails())
        {
            $errorresponse = [
                'status'    => $this->errorStatus,
                'message'   => $validator->errors()->first(),
                'response'  => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }
        $subscription_id = request('subscription_id');
        $order =  Order::where('subscription_id',$subscription_id)->first();
        $order->status = 'AC';
        $is_save = $order->save();
        if($is_save)
        {
            $response = [
                'status'    => 200,
                'title'     => 'Payment Success',
                'message'   => 'Plan successfully activated.',
                'response'  => 'success',
            ];
          
            return view('admin/template/Subscription/payment-status',compact('response'));
        }
        else
        {
            $response = [
                'status'    => $this->errorStatus,
                'title'     => 'Payment failed',
                'message'   => 'something went wrong!',
                'response'  => 'failed',
            ];
          
            return view('admin/template/Subscription/payment-status',compact('response'));
        }  
    }

    /**
     * if payment not complate return to app
     *
     * @param 
     * @return \Illuminate\Http\Response
     */
    public function cancelUrl()
    {
        $errorResponse = [
            'status'    => $this->errorStatus,
            'message'   => 'Payment not complate.',
            'response'  => 'failed',
        ];
        return response()->json($errorResponse, $this->errorStatus);
    }

    /**
     * purchase subscription order history
     *
     * @param 
     * @return \Illuminate\Http\Response
    */
    public function getOrderList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id'    => 'required|exists:users,id',
        ]);

        if($validator->fails())
        {
            $errorresponse = [
                'status'  => $this->errorStatus,
                'message' => $validator->errors()->first(),
                'response' => 'failed',
            ];
            return response()->json($errorresponse, $this->errorStatus);
        }

        $orders = DB::table('orders as o')
        ->join('membership_plans as plan','o.plan_id','=','plan.id')
        ->join('coupons as c','o.coupon_id','=','c.id')
        ->select('o.subscription_id','o.plan_id','o.coupon_id','plan.name as plan_name','plan.price','c.no_of_coupons','o.created_at as date','o.status')
        ->where('o.user_id',request('business_id'))
        ->orderBy('o.id','desc')
        ->get();

        $data = array();
        if(count($orders)>0)
        {
            foreach ($orders as $order) {
                $orderdate = new Carbon($order->date);
                $data[] = array(
                    'subscription_id'   =>   $order->subscription_id,
                    'plan_id'           =>   $order->plan_id,
                    'coupon_id'         =>   $order->coupon_id,
                    'plan_name'         =>   $order->plan_name,
                    'price'             =>   $order->price,
                    'no_of_coupons'      =>   $order->no_of_coupons,
                    'date'              =>   $orderdate->toFormattedDateString(),
                    'status'            =>   $order->status,
                );
            }
    
            $successResponse = [
                'status'    => $this->successStatus,
                'message'   => 'orders list',
                'response'  => 'success',
                'order_list' => $data
            ];
            return response()->json($successResponse, $this->successStatus);
        }
        else 
        {
            $successResponse = [
                'status'    => $this->successStatus,
                'message'   => 'order not found',
                'response'  => 'success',
                'order_list' => $data
            ];
            return response()->json($successResponse, $this->successStatus);
        }
    }

     /**
     * cancel subscription when all coupon are used
     *
     * @param 
     * @return \Illuminate\Http\Response
    */
    public function cancelSubscription($subscription_id)
    {
        $authObj = new SubscriptionController;
        $auth = $authObj->getAuthToken();
        
        $curl = curl_init();
        $url  = "https://api.sandbox.paypal.com/v1/billing/subscriptions/".$subscription_id."/cancel";
    
        $header = array(
            'Authorization:'.$auth->token_type." ".$auth->access_token, 
            'Content-Type:application/json'    
        ); 
        $params='{
            "reason": "All Coupons are used."
          }';

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$params);     
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CERTINFO, 0);
        
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);  
        return $result = json_decode($response);
    }

}


