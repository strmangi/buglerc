<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Gate;
use App\Models\Role;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Mail;
use App\Mail\SendMail;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //---get toady inquiries 
        $inquiries = DB::table('inquiries as i')
            ->join('customers as cr','i.customer_id', '=', 'cr.id')
            ->whereDate('i.trip_start_date',date('Y-m-d'))
            ->select('i.*','cr.name as cr_name','cr.email','cr.phone')
            ->get();
        //--get today duties--
        $duties = DB::table('coach_driver_trip as x')
            ->join('inquiries as i','x.trip_id','=','i.id')
            ->join('users as dv','x.driver_id','=','dv.id')
            ->join('coaches as ch','x.coach_id','=','ch.id')
            ->whereDate('i.trip_start_date',date('Y-m-d'))
            ->select('i.quotation_no','i.destination','i.pick_up_time','x.reporting_time','x.departure_time','i.driver_sheet_notes','i.trip_start_date','ch.registration_no','dv.name')
            ->get();
       
            return view('admin.template.dashboard',compact('inquiries','duties'));
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.template.Users.create_driver');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(),[
            'name'      => 'required|max:255|string',
            'email'     => 'required|string|email|unique:users,email|max:255',
            'password'  => 'required|string|min:6|confirmed',
            'status'    => 'required',
        ]);

        if($validation->fails()){
            //get all errors
            $error=$validation->errors();
            return response()->json(['error' => $error]);
        }
        if($validation->passes())
        {
            $data = array(
                   'name'      => $request->get('name'),
                   'email'     => $request->get('email'),
                   'password'  => $password = Hash::make($request->get('password')),
                   'status'    => $request->get('status'),
                );
        
            $user=User::create($data);

            $role=Role::select('id')->where('name','driver')->first();
           
            $user->roles()->attach($role);

            if($user)
            {
                // mail data array
                $data = array(
                   'name'      => $request->get('name'),
                   'email'     => $request->get('email'),
                   'password'  => $password = $request->get('password'),
                );

                $email =  $request->get('email');
                // $send =  Mail::to($email)->send(new SendMail($data));
                $send = TRUE;
                if($send){

                    $success = array('msg' =>' Driver has been created, Successfully.');
                    return response()->json(['success' =>$success]);    
                }else{
                    $success = array('msg' =>'Driver has been created.');
                    return response()->json(['success' =>$success]);    
                }
                
            }
            else
            {
                $error = array('msg' =>'Failed, Something went wrong!');
                return response()->json(['error' =>$error]);
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = DB::table('users as u')
            ->join('role_user as ru','u.id', '=', 'ru.user_id')
            ->where('ru.role_id','=',3)
            ->where('u.id','=',$id)
            ->select('u.name','u.email','u.id as id','u.password','ru.driver_booking_status')
            ->first();

        if(Gate::denies('edit-user')){
            return redirect(route('admin.users.index'));
        };

        return view('admin.template.Users.edit_driver', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $validation = Validator::make($request->all(),[
        'name'      => 'required|max:255|string',
        'email'     => 'required|string|email|unique:users,email,'.$id.'|max:255',
        'password'  => 'nullable|string|min:6|confirmed',
        // 'status'    => 'required',
        ])->validate();
        
        $user=User::find($id);

        $data = array(
                   'name'      => $request->get('name'),
                   'email'     => $request->get('email'),
                   'password'  => $request->password ? bcrypt($request->password) : $user->password,
                );
                   
        $is_update=User::where('id','=',$id)->update($data);
        // $data_status = array('driver_booking_status'  => $request->get('status'));
        // $is_status_update=DB::table('role_user')->where('user_id','=',$id)->where('role_id','=',3)->update($data_status);
       
        if($is_update){
           session()->flash("success","Update Successfully!");
            return redirect()->back();
        }else{
            session()->flash('info', 'Update failed!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::denies('delete-user')) {
            return redirect(route('admin.users.index'));
        };
        
        
        $user = User::find($id); 
        $user->delete(); //delete the client
        $is_delete = DB::table('users')->where('id',$id)->delete(); 


        if($is_delete){
            session()->flash("success","Driver has been deleted.");
            return redirect()->back();  
        }
        else{
            session()->flash("error","Something went wrong!");
            return redirect()->back(); 
        }
       
    }

    /**
    get user where role is driver
    */
    public function userDetail()
    {

        $users = DB::table('users as u')
            ->join('role_user as ru','u.id', '=', 'ru.user_id')
            ->where('ru.role_id','=',3)
            ->whereNull('u.deleted_at')
            ->select('u.name','u.email','u.id as id','u.password','ru.driver_booking_status')
            ->get();
        return view('admin.template.Users.user', compact('users'));
    }

    /**
     * Display the specified trip according to date.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
    */
    public function getTripByDate()
    {
      
        $inquiries = DB::table('inquiries as i')
            ->join('customers as cr','i.customer_id', '=', 'cr.id')
            ->whereDate('trip_start_date',$_POST['inquiry_search'])
            ->select('i.*','cr.name as cr_name','cr.email','cr.phone')
            ->count();
        $output = "";
        if($inquiries>0)
        {   
            $inquiries = DB::table('inquiries as i')
            ->join('customers as cr','i.customer_id', '=', 'cr.id')
            ->whereDate('trip_start_date',$_POST['inquiry_search'])
            ->select('i.*','cr.name as cr_name','cr.email','cr.phone')
            ->get();
            foreach($inquiries as $inquiry)
            {
                $output .= '<tr>'.
                  '<td>'.$inquiry->cr_name.'</td>'.
                  '<td>'.$inquiry->email.'<br>'.$inquiry->phone.'</td>'
                  .'<td>'.$inquiry->pick_up_point.'</td>'.
                  '<td>'.$inquiry->pick_up_time.'</td>'.
                  '<td>'
                    .date('d-M-y', strtotime($inquiry->trip_start_date)).' to '.date('d-M-y',strtotime($inquiry->return_date)).'</td>'
                  .'<td>';
                    if($inquiry->status=='Confirmed')
                      $output .= '<span class="badge badge-success">Confirmed</span>';
                    elseif($inquiry->status=='Quotation')
                       $output.= '<span class="badge badge-secondary">Quotation</span>';
                    elseif($inquiry->status=='Cancelled')
                      $output.= '<span class="badge badge-danger">Cancelled</span>';
                    else
                     $output.= '<span class="badge badge-info">'.$inquiry->status.'</span>';
                    
                  $output.= '</td>'.
                  '<td>'.
                    '<div class="row">'.
                     '<a href="'.route('admin.inquiry.edit', $inquiry->id).'" name="edit" title="edit" id="'.$inquiry->id.'" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i></a>
                     <a href="'.route('admin.inquiry.show', $inquiry->id).'" id="'.$inquiry->id.'" title="view-details" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;"><i class="far fa-eye"></i></a>'.
                                           
                    '</div>
                  </td>
                </tr>';
            }
        }
        else
        {
            $output .= '<tr>'.
                  '<td colspan="7">There is no trip available at this date.</td></tr>';
                   
        }
        echo $output;
    }

    /**
     * Display the specified duty according to date.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
    */
    public function getDutyByDate()
    {
        $duties = DB::table('coach_driver_trip as x')
            ->join('inquiries as i','x.trip_id','=','i.id')
            ->join('users as dv','x.driver_id','=','dv.id')
            ->join('coaches as ch','x.coach_id','=','ch.id')
            ->whereDate('i.trip_start_date',$_POST['duty_search'])
            ->select('i.quotation_no','i.destination','i.pick_up_time','x.reporting_time','x.departure_time','i.driver_sheet_notes','i.trip_start_date','ch.registration_no','dv.name')
            ->count();
        $output = "";

        if($duties>0)
        {   
            $duties = DB::table('coach_driver_trip as x')
            ->join('inquiries as i','x.trip_id','=','i.id')
            ->join('users as dv','x.driver_id','=','dv.id')
            ->join('coaches as ch','x.coach_id','=','ch.id')
            ->whereDate('i.trip_start_date',$_POST['duty_search'])
            ->select('i.quotation_no','i.destination','i.pick_up_time','x.reporting_time','x.departure_time','i.driver_sheet_notes','i.trip_start_date','ch.registration_no','dv.name')
            ->get();
            foreach ($duties as $duty){
            $date = date('D d-M-Y', strtotime($duty->trip_start_date));
            $output .= '<tr>'.
              '<td>'.$duty->destination.'</td>'.
              '<td>'.$duty->registration_no.'</td>'.
              '<td>'.$duty->name.'</td>'.
              '<td>'.$duty->reporting_time.'</td>'.
              '<td>'.$duty->departure_time.'</td>'.
              '<td>'.$date.'</td>'.
              '<td>'.$duty->driver_sheet_notes.'</td>';
            }
        }
        else
        {
             $output .= '<tr><td colspan="7">There is no duty available at this date.</td></tr>';
        }
        echo $output;
    }
}
