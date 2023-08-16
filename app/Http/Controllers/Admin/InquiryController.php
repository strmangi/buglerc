<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\CoachType;
use App\Models\Customer;
use App\Models\CoachTypesInquiries;
use App\Models\DriverTrip;
use App\Models\Coach;
use App\Models\PurchaseOrder;
use App\Models\OrderItem;
use App\Models\Supplier;
use App\Models\Booking;
use App\User;
use DB;
use Validator;
use Mail;
use URL;
use PDF;
use DateTime;
use App\Mail\SendQuotation;
use App\Mail\Quotation;
use App\Mail\SendConfirmation;
 use App\Models\ImportCsv;
use App\Imports\TripImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Datatables;


class InquiryController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index() {
      $inquiries = DB::table('inquiries as i')
        ->leftJoin('customers as cr', 'i.customer_id', '=', 'cr.id')
        ->leftJoin('coach_driver_trip as driver', 'i.id', '=', 'driver.trip_id')
        ->select('i.*', 'cr.name as cr_name', 'cr.contact_name as cr_contact_name', 'cr.email as cr_email', 'cr.phone as cr_phone', 'driver.created_at' , 'driver.driver_id as driver_id')
        //->distinct('driver.trip_id')
        ->orderBy('i.trip_start_date', 'desc')
        ->get()->unique('quotation_no')->pluck('trip_start_date');
        // echo "<pre>"; print_r($inquiries); die("check");
        return view('admin.template.Inquiry.inquiry', compact('inquiries'));
    }
    public function inquirieslist(Request $request) {

                if ($request->ajax()) {

                    $order = $request->input('order.0.column');
                    // $dir   = $request->input('order.0.dir', 'Desc');
                    $inquiries = Inquiry::select('inquiries.*', 'customers.name as cr_name', 'customers.contact_name as cr_contact_name', 'customers.email as cr_email', 'customers.phone as cr_phone', 'coach_driver_trip.created_at', 'coach_driver_trip.driver_id')
                    ->leftJoin('customers', 'inquiries.customer_id', '=', 'customers.id')
                    ->leftJoin('coach_driver_trip', 'inquiries.id', '=', 'coach_driver_trip.trip_id');
                    if (!empty($request->date)) {
                        $dateselect = date('Y-m-d', strtotime($request->date));
                        $data = $inquiries->whereDate('inquiries.trip_start_date', '=', $dateselect);
                    }
                    if($order == '9') {
                        $dir   = $request->input('order.0.dir'); 
                        $data = $inquiries->orderBy('inquiries.trip_start_date', $dir)->get();
                    } else {
                        $dir   = $request->input('order.0.dir'); 
                        $data = $inquiries->orderBy('inquiries.trip_start_date', $dir)->get()->unique('quotation_no');
                    }

                    return Datatables()->of($data)
                         ->addIndexColumn()
                         ->addColumn('checkbox_choose', function($row){
                            $checkbox_choose = '<input type="checkbox" name="id[]" value="'.$row->id.'">';
                            return $checkbox_choose;
                        })
                    ->addColumn('status', function($row){
                        if($row->status=='Confirmed'){
                        $status ='<span class="badge badge-success">Confirmed</span>';
                        }elseif($row->status=='Quotation'){
                        $status = '<span class="badge badge-secondary">Quotation</span>';
                        }elseif($row->status=='Cancelled'){
                        $status = '<span class="badge badge-danger">Cancelled</span>';
                        }else{
                        $status = '<span class="badge badge-info">'.$row->status.'</span>';
                        }
                        return $status;
                    })
                    ->addColumn('action', function($row){
                       $actionBtn = '<div class="row">
                       <a href="'.route('admin.inquiry.clone', $row->id).'" title="Clone Enquiry" id=""'.$row->id.'" class="edit text-light btn btn-success btn-sm" style="margin-right: 2px;"><i class="far fa-clone"></i></a>

                       <a href="'.route('admin.inquiry.print', $row->id).'" name="print" title="print" id="{{$inquiry->id}}" class="edit text-light btn btn-primary btn-sm" style="margin-right: 2px;"><i class="fas fa-print"></i></a>

                       <a href="'.route('admin.inquiry.edit', $row->id).'" name="edit" title="edit" id="'.$row->id.'" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i></a>

                       <a href="'.route('admin.inquiry.show', $row->id).'" id="'.$row->id.'" title="view-details" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;"><i class="far fa-eye"></i></a>';

                        if($row->status=='Confirmed'){

                         $actionBtn .= '<a href="'.route('admin.driver.assign',$row->id).'" id="'.$row->id.'" title="assign driver &     coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Assign</a>
                          <a href="'.route('admin.inquiry.sendConfirmMail',$row->id).'" id="'.$row->id.'" title="Send Confirm Mail" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Send Confirm Mail</a>';
                        }elseif($row->status=='Assigned'){

                        $actionBtn .= '<a href="'.route('admin.driver.assign',$row->id).'" id="'.$row->id.'" title="reassign driver &   coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">ReAssign</a>';
                     
                        }elseif($row->status=='Quotation'){

                           $actionBtn .= '<a href="'.route('admin.inquiry.conform',$row->id).'" id="'.$row->id.'" title="Confirm Inquiry" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Confirm</a>
                           <a href="'.route('admin.inquiry.SendEmailEnquery',$row->id).'" id="'.$row->id.'" title="Send Mail" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Send Mail</a>';
                        }
                        $actionBtn .= '<a  href="'.url('admin/delete_enquery', $row->id).'" id="'.$row->id.'" onclick="return confirm("Are you sure you want to delete this?")" title="view-details" class="edit text-light btn btn-danger btn-sm" style="margin-left: 2px;"><i class="fa fa-trash"></i></a></div>';
                            return $actionBtn;
                    })
                    ->editColumn('cr_name',function($row){
                        $cr_name = $row->cr_name;
                        return $cr_name;
                    }) 
                    ->editColumn('cr_contact_name',function($row){
                        $cr_contact_name = $row->cr_contact_name;
                        return $cr_contact_name;
                    })
                    ->editColumn('select_driver',function($row){
                        $selectdriver = array();
                        $select_driver = DriverTrip::where('trip_id', $row->id)->get();

                        foreach($select_driver as $driver){
                            $select_drivers = user::where('id', $driver->driver_id)->get();
                            foreach($select_drivers as $dirvers){
                                $selectdrivers[] = $dirvers->name;
                                $selectdriver = $selectdrivers; 
                            }
                        }
                        return $selectdriver;
                    })
                    ->editColumn('quotation_no',function($row){
                        $quotation_no = $row->quotation_no;
                        return $quotation_no;
                    })
                    ->editColumn('pick_up_point',function($row){
                        $pick_up_point = $row->pick_up_point;
                        return $pick_up_point;
                    })
                    ->editColumn('cr_phone',function($row){
                        $cr_phone = $row->cr_email.' '.$row->cr_phone;
                        return $cr_phone;
                    })
                    ->editColumn('pick_up_time',function($row){
                        if($row->pick_up_time){
                        $pick_up_time = date('H:i', strtotime($row->pick_up_time));
                        }
                        return $pick_up_time;
                    })
                    ->editColumn('trip_start_date',function($row){
                        if($row->return_date){
                            $returnDate = ' to '. date('d-M-y',strtotime($row->return_date));
                        }else{
                            $returnDate = ' One Way'; 
                        }

                        if(!empty($row->trip_start_date)){
                        return $start_date = date('Y-m-d', strtotime($row->trip_start_date));
                        }
                        
                    })
                    ->editColumn('report_time',function($row){
                        if($row->report_time){
                            $report_time = date('H:i', strtotime($row->report_time));
                        }else{
                            $report_time =''; 
                        }
                        return $report_time;
                    })
                    ->editColumn('trip_end_date',function($row){
                        if($row->trip_end_date){
                            $returnData = date('d-M-y',strtotime($row->return_date)); 
                        }else{
                            $returnData = date('d-M-y',strtotime($row->trip_start_date));
                        }
                        return $returnData;
                    })
                    ->editColumn('return_time',function($row){
                        if($row->return_time){
                            $return_time = date('H:i', strtotime($row->return_time));
                            return $return_time;
                        }
                    })
                    ->rawColumns(['checkbox_choose', 'status', 'action'])->make(true);
                }
        


    }

    public function school() {

        $inquiries = DB::table('inquiries as i')
        ->leftJoin('customers as cr','i.customer_id', '=', 'cr.id')
        ->leftJoin('coach_driver_trip as driver', 'i.id', '=', 'driver.trip_id')
        ->select('i.*','cr.name as cr_name','cr.contact_name as cr_contact_name','cr.email as cr_email','cr.phone as cr_phone', 'driver.created_at' ,  'driver.driver_id as driver_id')
        // ->orderBy('driver.created_at', 'desc')
        ->where('is_school','1')
        ->get()->unique('quotation_no');
        //  echo "<pre>";print_r($inquiries->toArray());die;
        return view('admin.template.Inquiry.school_inquiry', compact('inquiries'));
    }

    public function schoolinquirieslist(Request $request) {

        if ($request->ajax()) {

            $order = $request->input('order.0.column');
            $inquiries = DB::table('inquiries as i')
            ->leftJoin('customers as cr','i.customer_id', '=', 'cr.id')
            ->leftJoin('coach_driver_trip as driver', 'i.id', '=', 'driver.trip_id')
            ->select('i.*','cr.name as cr_name','cr.contact_name as cr_contact_name','cr.email as cr_email','cr.phone as cr_phone', 'driver.created_at' ,  'driver.driver_id as driver_id')
            // ->orderBy('driver.created_at', 'desc')
            ->where('is_school','1');
            if($order == '2') {
                $dir   = $request->input('order.0.dir'); 
                $data = $inquiries->orderBy('i.trip_start_date', $dir)->get();
            } else {
                $dir   = $request->input('order.0.dir'); 
                $data = $inquiries->orderBy('i.trip_start_date', $dir)->get()->unique('quotation_no');
            }
            // echo "<pre>";print_r($data->toArray());die;
            return Datatables()->of($data)
                 ->addIndexColumn()
            ->addColumn('checkbox_choose', function($row){
                $checkbox_choose = '<input type="checkbox" name="id[]" value="'.$row->id.'">';
                return $checkbox_choose;
            })
            ->editColumn('quotation_no',function($row){
                $quotation_no = $row->quotation_no;
                return $quotation_no;
            })
            ->editColumn('trip_start_date',function($row){
                if($row->return_date){
                    $returnDate = ' to '. date('d-M-y',strtotime($row->return_date));
                }else{
                    $returnDate = ' One Way'; 
                }

                if(!empty($row->trip_start_date)){
                return $start_date = date('Y-m-d', strtotime($row->trip_start_date));
                }
                
            })
            ->editColumn('cr_name',function($row){
                $cr_name = $row->cr_name;
                return $cr_name;
            }) 
            ->editColumn('select_driver',function($row){
                $selectdriver = array();
                $select_driver = DriverTrip::where('trip_id', $row->id)->get();

                foreach($select_driver as $driver){
                    $select_drivers = user::where('id', $driver->driver_id)->get();
                    foreach($select_drivers as $dirvers){
                        $selectdrivers[] = $dirvers->name;
                        $selectdriver = $selectdrivers; 
                    }
                }
                return $selectdriver;
            })
            ->editColumn('pick_up_point',function($row){
                $pick_up_point = $row->pick_up_point;
                return $pick_up_point;
            })
            ->editColumn('pick_up_time',function($row){
                if($row->pick_up_time){
                $pick_up_time = date('H:i', strtotime($row->pick_up_time));
                }
                return $pick_up_time;
            })
            ->editColumn('report_time',function($row){
                if($row->report_time){
                    $report_time = date('H:i', strtotime($row->report_time));
                }else{
                    $report_time =''; 
                }
                return $report_time;
            })
            ->editColumn('job_end_time',function($row){
                if($row->job_end_time){
                    $job_end_time = date('H:i', strtotime($row->job_end_time));
                }else{
                    $job_end_time =''; 
                }
                return $job_end_time;
            })
            ->addColumn('status', function($row){
                if($row->status=='Confirmed'){
                $status ='<span class="badge badge-success">Confirmed</span>';
                }elseif($row->status=='Quotation'){
                $status = '<span class="badge badge-secondary">Quotation</span>';
                }elseif($row->status=='Cancelled'){
                $status = '<span class="badge badge-danger">Cancelled</span>';
                }else{
                $status = '<span class="badge badge-info">'.$row->status.'</span>';
                }
                return $status;
            })
            ->addColumn('action', function($row){
               $actionBtn = '<div class="row">
              
               <a href="'.route('admin.inquiry.print', $row->id).'" name="print" title="print" id="'.$row->id.'" class="edit text-light btn btn-primary btn-sm" style="margin-right: 2px;"><i class="fas fa-print"></i></a>

               <a href="'.url('admin/inquiry_school/edit', $row->id).'" name="edit" title="edit" id="'.$row->id.'" class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i></a>

               <a href="'.route('admin.inquiry.show', $row->id).'" id="'.$row->id.'" title="view-details" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;"><i class="far fa-eye"></i></a>';

               $actionBtn .= '<a  href="'.url('admin/delete_enquery', $row->id).'" id="'.$row->id.'" onclick="return confirm("Are you sure you want to delete this?")" title="view-details" class="edit text-light btn btn-danger btn-sm" style="margin-left: 2px;"><i class="fa fa-trash"></i></a></div>';

                if($row->status=='Confirmed'){

                  $actionBtn .= ' <a href="'.route('admin.driver.assign',$row->id).'" id="'.$row->id.'" title="assign driver & coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Assign</a>'; 

                } elseif($row->status=='Quotation'){

                   $actionBtn .= ' <a href="'.route('admin.inquiry.conform',$row->id).'" id="'.$row->id.'" title="Confirm Inquiry" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Confirm</a>';
                }
                    return $actionBtn;
            })
            ->rawColumns(['checkbox_choose', 'status', 'action'])->make(true);
        }
    }

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(Request $request)

    {

        $status = 0;

        if ($request->status) {

            $status = $request->status;
        }

        $coach_types = CoachType::where('status', '1')->get();

        $customers = Customer::where('status', '1')->get();



        return view('admin.template.Inquiry.create_inquiry', compact('coach_types', 'customers', 'status'));
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {


        $validation = Validator::make($request->all(), [

            'customer_id'            => 'nullable|string|exists:customers,id',

            'customer_name'          => 'nullable|string|max:255',

            'email'                  => 'nullable|email|unique:customers,email',

            'phone'                  => 'nullable',

            'address'                => 'nullable|string',

            'Pickup_point'           => 'required|string|max:255',

            'Pickup_time'            => 'nullable',

            'report_time'            => 'nullable',

            'Pickup_time_not_fix'    => 'nullable|string',

            'Destination'            => 'required|string',

            'Trip_Start_Date'        => 'required|date',

            'return_date'            => 'nullable|date|after_or_equal:Trip_Start_Date',

            'is_one_way'             => 'nullable',

            'total_mileage'          => 'required|integer',

            'driver_hours'           => 'required|max:11',

            'coach_type'             => 'required|max:11',

            'supplemental_costs_1'   => 'nullable|max:11',

            'supplemental_costs_2'   => 'nullable|max:11',

            'supplemental_costs_3'   => 'nullable|max:11',



            'no_of_coaches'          => 'required|max:11',

            'CostPerMile'            => 'required|max:11',

            'no_of_days'             => 'required|max:11',

            'cost_for_day'           => 'required|max:11',

            'coach_type'             => 'required|max:11',

            'coach_type'             => 'required|max:11',

            'total_charge_per_coach' => 'required',



            'deposit_required'    => 'required',

            'balance_outstanding' => 'required',

            'total_charge'        => 'required',

            'deposit_received'    => 'nullable',

            'no_of_passengers'    => 'required|max:11',

            'no_of_wheelchairs'   => 'nullable|max:11',

            'driver_sheet_notes'  => 'nullable',

            'luggage'             => 'required',

            'status'              => 'required',

            'job_end_time'        => 'required',

            'trip_end_date'       => 'required',

            'user_id'             => 'required|max:10|min:1',

        ]);



        // echo "<pre>"; print_r($request->all()); die("check");



        if ($validation->fails()) {

            $error = $validation->errors();

            if ($request->ajax()) {

                //dd('ajax');

                return response()->json(['error' => $error]);
            } else {

                return redirect()->back()->withInput()->withErrors($error);
            }
        }







        $pickup_time = $request->get('Pickup_time');

        if ($request->get('Pickup_time_not_fix') == 'not_fix') {

            $pickup_time = NULL;
        }



        if (!empty(request('customer_id')) || !empty(request('email'))) {

             $quotation_no = $this->generateQuatationNo();



            if (!empty($request->get('customer_id'))) {



                $customer_id = $request->get('customer_id');
            } else {





                $customer_data = [

                    'name'                  => $request->get('customer_name') ?? NULL,

                    'email'                => $request->get('email') ?? NULL,

                    'phone'                => $request->get('phone') ?? NULL,

                    'address'              => $request->get('address') ?? NULL,

                    'contact_name'         => $request->get('contact_name') ?? NULL,

                ];

                $customer_id = Customer::insertGetId($customer_data);
            }



            $trip_data = array(

                'customer_id'          => $customer_id,

                'pick_up_point'        => $request->get('Pickup_point'),



                'pick_up_time'         => $pickup_time,


                'report_time'          => $request->get('report_time'),



                'destination'          => $request->get('Destination'),



                'trip_start_date'      => $request->get('Trip_Start_Date'),



                'trip_starting_time'   => strtotime($request->get('Trip_Start_Date') . ' ' . $pickup_time),



                'is_one_way'           => $request->get('is_one_way') ?? NULL,

                'return_date'          => empty($request->get('is_one_way')) ? $request->get('return_date') : NULL,

                'return_time'          => empty($request->get('is_one_way')) ? $request->get('return_time') : NULL,

                'trip_end_time'        => empty($request->get('is_one_way')) ? strtotime($request->get('return_date') . ' ' . $request->get('return_time')) : NULL,

                'total_mileage'        => $request->get('total_mileage'),

                'no_of_driver_hours'   => $request->get('driver_hours'),

                'supplemental_costs_1' => $request->get('supplemental_costs_1'),

                'supplemental_costs_2' => $request->get('supplemental_costs_2'),

                'supplemental_costs_3' => $request->get('supplemental_costs_3'),



                'deposit_required'     => $request->get('deposit_required'),

                'balance_outstanding'  => $request->get('balance_outstanding'),

                'total_charge'         => $request->get('total_charge'),

                'deposit_received'     => $request->get('deposit_received') ?? 0,

                'quated_by'            => $request->get('user_id'),

                'quotation_no'         => $quotation_no,

                'no_of_passengers'     => $request->get('no_of_passengers'),

                'no_of_wheelchairs'    => $request->get('no_of_wheelchairs'),

                'booking_date'         => date('Y-m-d'),

                'driver_sheet_notes'   => $request->get('driver_sheet_notes') ?? '',

                'luggage'              => $request->get('luggage') ?? 'NO',

                'status'               => $request->get('status'),

                'job_end_time'         => $request->get('job_end_time'),

                'trip_ending_time'     => strtotime($request->get('trip_end_date') . ' ' . $request->get('job_end_time')),

                'trip_end_date'         => $request->get('trip_end_date'),

                'created_at'           => date('Y-m-d H:i:s'),

                'is_school'             => $request->is_school

            );





            $trip_save = Inquiry::insertGetId($trip_data);



      

            if ($trip_save) {

                //get array

                $no_of_coaches          = $request->get('no_of_coaches');

                $CostPerMile            = $request->get('CostPerMile');

                $no_of_days             = $request->get('no_of_days');

                $cost_for_day           = $request->get('cost_for_day');

                $coach_type             = $request->get('coach_type');

                $cost_for_driver        = $request->get('cost_for_driver');

                $total_charge_per_coach = $request->get('total_charge_per_coach');



                $count = count($no_of_coaches); //get array lenth

                $data = array();

                $no_of_coaches = $request->get('no_of_coaches');

                for ($i = 0; $i < $count; $i++) {

                    if ($no_of_coaches[$i] != NULL) {

                        $new = array(

                            'coach_type_id' => $coach_type[$i],

                            'inquiry_id'    => $trip_save,

                            'no_of_coach'   => $no_of_coaches[$i],

                            'cost_per_mile' => $CostPerMile[$i],

                            'no_of_days'    => $no_of_days[$i],

                            'cost_per_day'  => $cost_for_day[$i],

                            'driver_per_hour_cost'  => $cost_for_driver[$i],

                            'total_charge'  => $total_charge_per_coach[$i],

                        );



                        array_push($data, $new);

                        $new = [];
                    }
                }

                $coach_save = CoachTypesInquiries::insert($data);



                if ($coach_save) {

                    //-----mail -------

                    $inqueryDetail = Inquiry::where('id', $trip_save)->first();



                    $custome  = Customer::find($customer_id);

                    $customer_name    = $custome->name;

                    $customer_email   = $custome->email;

                    $customer_phone   = $custome->phone;

                    $customer_address = $custome->address;



                    $user = User::find($request->get('user_id'))->first();

                    $inqueryDetail = Inquiry::find($trip_save);



                    $getCoach = DB::table('coach_types as ct')

                        ->join('coach_types_inquiries as ct_inq', 'ct.id', '=', 'ct_inq.coach_type_id')

                        ->where('ct_inq.inquiry_id', '=', $trip_save)

                        ->select('ct.type', 'ct_inq.no_of_coach', 'ct_inq.cost_per_mile', 'ct_inq.no_of_days', 'ct_inq.cost_per_day', 'ct_inq.total_charge as cost_per_coach')

                        ->get()->toArray();







                    $data = array(

                        'name'                => $customer_name,

                        'email'               => $customer_email,

                        'phone'               => $customer_phone,

                        'address'             => $customer_address,

                        'quotation_no'        => $inqueryDetail->quotation_no,

                        'pick_up_point'       => $request->get('Pickup_point'),

                        'pick_up_time'        => $request->get('Pickup_time'),

                        'report_time'          => $request->get('report_time'),

                        'destination'         => $request->get('Destination'),

                        'trip_start_date'     => date('D d-m-Y', strtotime($request->get('Trip_Start_Date'))),

                        'return_date'         => empty($request->get('is_one_way')) ? date('D d-m-Y', strtotime($request->get('return_date'))) : 'One Way',

                        'return_time'         => empty($request->get('is_one_way')) ? $request->get('return_time') : '',

                        'quated_by'           => $user->name,

                        'deposit_required'    => $request->get('deposit_required'),

                        'deposit_received'    => $request->get('deposit_received'),

                        'balance_outstanding' => $request->get('balance_outstanding'),

                        'total_charge'        => round($request->get('total_charge')),



                        'no_of_passengers'   => $request->get('no_of_passengers'),

                        'no_of_wheelchairs'  => $request->get('no_of_wheelchairs'),

                        'booking_date'       => date('Y-m-d'),

                        'driver_sheet_notes' => $request->get('driver_sheet_notes'),

                        'coach_detail'       => $getCoach,

                    );

                    // $send =  Mail::to($customer_email)->send(new SendQuotation($data));

                    //------/mail----



                    if ($request->ajax()) {

                        $success = array('msg' => 'Enquiry Created Successfully.');

                        return response()->json(['success' => $success]);

                        exit();
                    } else {

                        session()->flash("success", "Enquiry cloned Successfully!");

                        return redirect()->route('admin.inquiry.index');
                    }
                }

                $failed = array('msg' => 'Failed, Something went wrong!');

                return response()->json(['failed' => $failed]);
            } else {

                $failed = array('msg' => 'Failed, Something went wrong!');

                return response()->json(['failed' => $failed]);
            }
        } else {





            $failed = array('msg' => 'Choose customer or fill customer details');

            return response()->json(['failed' => $failed]);

            exit();
        }
    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        // dd($id);

        $inquiries = DB::table('inquiries as i')

            ->leftJoin('customers as cr', 'i.customer_id', '=', 'cr.id')

            ->join('coach_types_inquiries as coch_inq', 'i.id', '=', 'coch_inq.inquiry_id')

            ->join('coach_types as ct', 'coch_inq.coach_type_id', '=', 'ct.id')

            ->select('i.*', 'cr.name as cr_name', 'cr.email as cr_email', 'cr.phone as cr_phone', 'ct.type as coachType', 'coch_inq.no_of_coach', 'coch_inq.total_charge as charge_for_this_coach')

            ->where('i.id', '=', $id)

            ->get();

        // dd($inquiries);

        return view('admin.template.Inquiry.inquiry_details', compact('inquiries'));
    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $inquiry = DB::table('inquiries as i')

            ->where('i.id', '=', $id)

            ->first();

        $coach_types = CoachType::where('status', '1')->get();

        $customers = Customer::all();



        $coaches = DB::table('coach_types_inquiries as coch_inq')

            ->join('coach_types as ct', 'coch_inq.coach_type_id', '=', 'ct.id')

            ->where('coch_inq.inquiry_id', '=', $id)

            ->select('coch_inq.*', 'ct.type', 'ct.cost_per_mile', 'ct.cost_per_day', 'ct.cost_per_driver')

            ->get();

        //dd($inquiry);

        return view('admin.template.Inquiry.edit_inquiry', compact('inquiry', 'coaches', 'customers', 'coach_types'));
    }



    public function school_edit($id)

    {

        $inquiry = DB::table('inquiries as i')

            ->where('i.id', '=', $id)

            ->first();

        $coach_types = CoachType::where('status', '1')->get();

        $customers = Customer::all();



        $coaches = DB::table('coach_types_inquiries as coch_inq')

            ->join('coach_types as ct', 'coch_inq.coach_type_id', '=', 'ct.id')

            ->where('coch_inq.inquiry_id', '=', $id)

            ->select('coch_inq.*', 'ct.type', 'ct.cost_per_mile', 'ct.cost_per_day', 'ct.cost_per_driver')

            ->get();

        //dd($inquiry);

        return view('admin.template.Inquiry.school_edit_inquery', compact('inquiry', 'coaches', 'customers', 'coach_types'));
    }



    /**

     * Inquiry print.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function printInquiry($id)

    {

        $inquiry = DB::table('inquiries as i')

            ->where('i.id', '=', $id)

            ->first();

        $coach_types = CoachType::where('status', '1')->get();

        $customers = Customer::all();



        $coaches = DB::table('coach_types_inquiries as coch_inq')

            ->join('coach_types as ct', 'coch_inq.coach_type_id', '=', 'ct.id')

            ->where('coch_inq.inquiry_id', '=', $id)

            ->select('coch_inq.*', 'ct.type', 'ct.cost_per_mile', 'ct.cost_per_day', 'ct.cost_per_driver')

            ->get();

      



        return view('admin.template.Inquiry.edit_inquiry', compact('inquiry', 'coaches', 'customers', 'coach_types'));
    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    

    public function update(Request $request, $id)

    {

        $validation = Validator::make($request->all(), [

            'customer_id'            => 'required|string|max:255',

            'Pickup_point'           => 'required|string|max:255',

            'Pickup_time'            => 'nullable',

            'report_time'            => 'nullable',

            'Pickup_time_not_fix'    => 'nullable',

            'Destination'            => 'required|string',

            'Trip_Start_Date'        => 'required',

            'return_date'            => 'nullable',

            'is_one_way'             => 'nullable',

            'total_mileage'          => 'required|max:11',

            'driver_hours'           => 'required|max:11',

            'coach_type'             => 'required|max:11',

            'supplemental_costs_1'   => 'nullable|max:11',

            'supplemental_costs_2'   => 'nullable|max:11',

            'supplemental_costs_3'   => 'nullable|max:11',

            'no_of_coaches'          => 'required|max:11',

            'CostPerMile'            => 'required|max:11',

            'no_of_days'             => 'required|max:11',

            'cost_for_day'           => 'required|max:11',

            'coach_type'             => 'required|max:11',

            'coach_type'             => 'required|max:11',

            'total_charge_per_coach' => 'required',

            'deposit_required'       => 'required',

            'balance_outstanding'    => 'required',

            'total_charge'           => 'required',

            'deposit_received'       => 'nullable',

            'no_of_passengers'       => 'required|max:11',

            'no_of_wheelchairs'      => 'nullable|max:11',

            'driver_sheet_notes'     => 'nullable',

            'status'                 => 'required',

            'user_id'                => 'required|max:10|min:1',

            'status'                 => 'required',

        ])->validate();



        $trip_data = array(

            'customer_id'          => $request->get('customer_id'),

            'pick_up_point'        => $request->get('Pickup_point'),

            'pick_up_time'         => $request->get('Pickup_time'),

            'report_time'          => $request->get('report_time'),

            'destination'          => $request->get('Destination'),

            'trip_start_date'      => $request->get('Trip_Start_Date'),

            'return_date'          => $request->get('return_date'),

            'return_time'          => $request->get('return_time'),

            'is_one_way'           => $request->get('is_one_way') ?? NULL,

            'total_mileage'        => $request->get('total_mileage'),

            'no_of_driver_hours'   => $request->get('driver_hours'),

            'supplemental_costs_1' => $request->get('supplemental_costs_1'),

            'supplemental_costs_2' => $request->get('supplemental_costs_2'),

            'supplemental_costs_3' => $request->get('supplemental_costs_3'),

            'deposit_required'     => $request->get('deposit_required'),

            'balance_outstanding'  => $request->get('balance_outstanding'),

            'total_charge'         => $request->get('total_charge'),

            'deposit_received'     => $request->get('deposit_received') ?? 0,

            'no_of_passengers'     => $request->get('no_of_passengers'),

            'no_of_wheelchairs'    => $request->get('no_of_wheelchairs'),

            'booking_date'         => date('Y-m-d'),

            'driver_sheet_notes'   => $request->get('driver_sheet_notes') ?? '',

            'status'               => $request->get('status'),

            'created_at'           => date('Y-m-d H:i:s'),

        );



        $check_inq = Inquiry::find($id);

        if ($check_inq != true) {

            session()->flash('error', 'Invalid Enquiry!');

            return redirect()->back();

            exit();
        }



        if ($request->get('status') != 'Completed') {



            $is_update = Inquiry::where('id', '=', $id)->update($trip_data);
        } else {



            $is_update = Inquiry::where('id', '=', $id)->update($trip_data);

            $data = DB::table('coach_driver_trip as x')

                ->where('x.trip_id', '=', $id)

                ->get();

            foreach ($data as $value) {

                $coach_update = Coach::where('id', '=', $value->coach_id)->update(['status' => 'Available']);

                $driver_update = DB::table('role_user')

                    ->where(['user_id' => $value->driver_id, 'role_id' => '3'])

                    ->update(['driver_booking_status' => 'Available']);
            }
        }

        //get array

        $no_of_coaches   = $request->get('no_of_coaches');

        $CostPerMile     = $request->get('CostPerMile');

        $no_of_days      = $request->get('no_of_days');

        $cost_for_day    = $request->get('cost_for_day');

        $coach_type      = $request->get('coach_type');

        $cost_for_driver    = $request->get('cost_for_driver');

        $total_charge_per_coach = $request->get('total_charge_per_coach');



        $count = count($no_of_coaches); //get array lenth

        $data = array();

        $no_of_coaches = $request->get('no_of_coaches');

        for ($i = 0; $i < $count; $i++) {

            if ($no_of_coaches[$i] != NULL) {

                $new = array(

                    'coach_type_id' => $coach_type[$i],

                    'inquiry_id'    => $id,

                    'no_of_coach' => $no_of_coaches[$i],

                    'cost_per_mile' => $CostPerMile[$i],

                    'no_of_days'    => $no_of_days[$i],

                    'cost_per_day'  => $cost_for_day[$i],

                    'driver_per_hour_cost'  => $cost_for_driver[$i],

                    'total_charge'  => $total_charge_per_coach[$i],

                );



                array_push($data, $new);

                $new = [];
            }
        }

        $check_delete = CoachTypesInquiries::where('inquiry_id', $id)->delete();

        if ($check_delete) {

            $coach_save = CoachTypesInquiries::insert($data);

            if ($coach_save) {

                session()->flash("success", "Update Successfully!");

                return redirect()->back();
            } else {

                session()->flash("error", "Something went wrong!");

                return redirect()->back();
            }
        }
    }





    public function school_update(Request $request, $id)

    {

        // die("DF");



        $validation = Validator::make($request->all(), [

            'customer_id'            => 'required|string|max:255',

            'Pickup_point'           => 'required|string|max:255',

            'Pickup_time'            => 'nullable',

            'report_time'            => 'nullable',

            'Pickup_time_not_fix'    => 'nullable',

            'Destination'            => 'required|string',

            'no_of_passengers'       => 'required|max:11',

            'coach_type'             => 'required|max:11',

            // 'status'                 => 'required',

        ])->validate();



        $trip_data = array(

            'customer_id'          => $request->get('customer_id'),

            'pick_up_point'        => $request->get('Pickup_point'),

            'pick_up_time'         => $request->get('Pickup_time'),
            

            'destination'          => $request->get('Destination'),

            'no_of_passengers'          => $request->get('no_of_passengers'),


            'created_at'           => date('Y-m-d H:i:s'),

        );



        $check_inq = Inquiry::find($id);

        if ($check_inq != true) {

            session()->flash('error', 'Invalid Enquiry!');

            return redirect()->back();

            exit();
        }



        if ($request->get('status') != 'Completed') {



            $is_update = Inquiry::where('id', '=', $id)->update($trip_data);
        } else {



            $is_update = Inquiry::where('id', '=', $id)->update($trip_data);

            $data = DB::table('coach_driver_trip as x')

                ->where('x.trip_id', '=', $id)

                ->get();

            foreach ($data as $value) {

                $coach_update = Coach::where('id', '=', $value->coach_id)->update(['status' => 'Available']);

                $driver_update = DB::table('role_user')

                    ->where(['user_id' => $value->driver_id, 'role_id' => '3'])

                    ->update(['driver_booking_status' => 'Available']);
            }
        }

        //get array

        $no_of_coaches   = $request->get('no_of_coaches') ??  array();

        $CostPerMile     = $request->get('CostPerMile') ??  0;

        $no_of_days      = $request->get('no_of_days') ??  0;

        $cost_for_day    = $request->get('cost_for_day') ??  0;

        $coach_type      = $request->get('coach_type') ??  0;

        $cost_for_driver    = $request->get('cost_for_driver') ??  0;

        $total_charge_per_coach = $request->get('total_charge_per_coach') ??  0;


        $data = array();

       

        $update = array('coach_type_id' => $coach_type[0],);

        $check_delete = CoachTypesInquiries::where('inquiry_id', $id)->update($update);

        if ($check_delete) {

            session()->flash("success", "Update Successfully!");

            return redirect()->back();

            // $coach_save=CoachTypesInquiries::insert($data);

            // if($coach_save){

            // }



        } else {

            session()->flash("error", "Something went wrong!");

            return redirect()->back();
        }
    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        //

    }



    //Generate Random and unique quotation_no

    public function generateQuatationNo()
    {


            $rndno = rand(1111, 9999);
            // $t=time();
            $quotation_no = $rndno;
            return $quotation_no;
    }



    /*

     ***

        driver daily duties

     **

    */

    public function dutyIndex()

    {



        $customers = Customer::all();

        $duties = DB::table('coach_driver_trip as x')->join('inquiries as i', 'x.trip_id', '=', 'i.id')->join('customers as cr', 'cr.id', '=', 'i.customer_id')->join('users as dv', 'x.driver_id', '=', 'dv.id')->join('coaches as ch', 'x.coach_id', '=', 'ch.id')->select('x.id', 'i.destination', 'x.reporting_time', 'x.departure_time', 'x.slot_time', 'i.trip_start_date', 'ch.registration_no', 'dv.name', 'dv.id as driverID', 'dv.email', 'i.driver_sheet_notes', 'cr.name as customer_name', 'i.id as inqId', 'i.invoice as invoiceId')->orderBy('i.trip_start_date', 'desc')->get();


        return view('admin.template.Inquiry.driver_duties', compact('duties', 'customers'));
    }



    public function updateinvoice(Request $request)
    {

        // $status = Inquiry::where('id', $request->invoiceId)->update(['invoice' => $request->inqId]);

        $status = DB::table('inquiries')

            ->where('id', '=', $request->inqId)

            ->update(['invoice' => $request->invoiceId]);



        if (!$status) {

            $data = [

                'status' => false,

                'message'   => "Failed to update invoice ID."

            ];

            return json_encode($data);
        }



        $data = [

            'status' => true,

            'message'   => "Invoice Id updated successfully."

        ];

        return json_encode($data);
    }



     public function sendmailInstruction($id = '', $sheetID = '')

    {



        $user = User::find($id);

             $data = DB::table('coach_driver_trip as x')

            ->join('users as u', 'u.id', '=', 'x.driver_id')

            ->join('inquiries as i', 'i.id', '=', 'x.trip_id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('coaches as c', 'c.id', '=', 'x.coach_id')

            ->where('x.id', $sheetID)

            ->select('x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.id', 'i.quotation_no', 'i.pick_up_time','i.return_time', 'i.destination', 'i.no_of_passengers', 'i.trip_start_date', 'i.total_charge', 'i.balance_outstanding',  'i.deposit_received', 'i.deposit_required', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name')

            ->first();

        $coach_driver_trip_id = $sheetID;

        $mailData['data'] = $data;

        $mailData['coach_driver_trip_id'] = $sheetID;
        $user->customer_email = $data->email;
        // echo "<pre>";print_r($data);die;
        $flag = Mail::send('customer_instruction_sheet_mail', $mailData, function ($message) use ($user) {

            //$user->email
            $customer_emal = $user['customer_email'];
            $admin_emal = $user['customer_email'];

            $message->to($customer_emal)->to($admin_emal)->subject('Customer Confirmation Instruction Sheet');



            $message->from(env('MAIL_FROM_ADDRESS'));
        });



        session()->flash("success", "mail Send  Successfully to Driver !");

        return redirect()->back();







        // echo "<pre>";

        // print_r($id);

        // echo "<br>";

        // print_r($sheetID);

        // die("Df");

    }


public function getInquiryBydate(){

    $inquiries = DB::table('inquiries as i')

    ->leftJoin('customers as cr', 'i.customer_id', '=', 'cr.id')
    ->leftJoin('coach_driver_trip as driver', 'i.id', '=', 'driver.trip_id');

    if (!empty($_POST['duty_date'])) {

        $inquiries = $inquiries->whereDate('i.trip_start_date', $_POST['duty_date']);
    }

    if (!empty($_POST['customer_id'])) {

        $inquiries = $inquiries->where('i.customer_id', $_POST['customer_id']);
    }

    $inquiries->select('i.*', 'cr.name as cr_name', 'cr.contact_name as cr_contact_name', 'cr.email as cr_email', 'cr.phone as cr_phone', 'driver.created_at' , 'driver.driver_id as driver_id')->orderBy('driver.created_at', 'desc');
    
    

   $data =  $inquiries->get();
   
   $output = "";

   if (count($data) > 0) {

       $output = "";

       foreach ($data as $inquiry) {

           $date = date('D d-M-Y', strtotime($inquiry->trip_start_date));

           if($inquiry->pick_up_time){
            $pickup_time =   date('H:i', strtotime($inquiry->pick_up_time));
           }

           if($inquiry->return_date){
             $returnData = ' to '. date('d-M-y',strtotime($inquiry->return_date)); 
           }else{
             $returnData = ' One Way';
           }

           if(!empty($inquiry->trip_start_date)){
            $datepart =  date('d-M-y', strtotime($inquiry->trip_start_date)).' '. $returnData;
           }

           $select_driver = User::where('id', $inquiry->driver_id)->first();

           $asgndriver = $select_driver?$select_driver->name:"";

        
                      if($inquiry->status=='Confirmed'){
                        $status = '<span class="badge badge-success">Confirmed</span>';

                   

                       }elseif($inquiry->status=='Quotation'){
                        $status = ' <span class="badge badge-secondary">Quotation</span>';
                      

                        } elseif($inquiry->status=='Cancelled'){
                            $status = '<span class="badge badge-danger">Cancelled</span>';
                        }else{
                            $status = '<span class="badge badge-info">'.$inquiry->status.'</span>';
                        }

                  
                  if($inquiry->return_date) :

                      $returnData = date('d-M-y',strtotime($inquiry->return_date));


                    else :

                     $returnData = date('d-M-y',strtotime($inquiry->trip_end_date)); 
                       
                    endif;
                    
                      if($inquiry->return_time) :

                      $return_time = date('H:i', strtotime($inquiry->return_time));

                    endif;
                    
                    if($inquiry->return_time) :

                      $return_timee = date('H:i', strtotime($inquiry->return_time));

                    endif;
                    
                 
                    
                    

             



           $output .= '<tr>' .
               '<th> <input type="checkbox" name="id[]" value="'.$inquiry->id.'"></th>'.

               '<td>' . $inquiry->quotation_no . '</td>' .

               '<td>' . $inquiry->cr_name . '</td>' .

               '<td>' . $inquiry->cr_contact_name . '</td>' .

               '<td>' .$asgndriver. '</td>' .

               '<td>' . $inquiry->cr_email .'<br>'.$inquiry->cr_phone. '</td>' .

               '<td>' . $inquiry->pick_up_point . '</td>' .

               '<td>' .$pickup_time . '</td>' .

               '<td>' . $datepart . '</td>' .

               '<td>' . date('H:i', strtotime($inquiry->report_time)) . '</td>' .
               
               '<td>' . $returnData . '</td>' .

               '<td>' . $return_time . '</td>' .

               '<td>' . $status . '</td>' .



               '<td>
               <div class="row">
               <a href='.route('admin.inquiry.clone', $inquiry->id).' title="Clone Enquiry" id='.$inquiry->id.' class="edit text-light btn btn-success btn-sm" style="margin-right: 2px;"><i class="far fa-clone"></i></a>
  
               <a href='.route('admin.inquiry.print', $inquiry->id).' name="print" title="print" id='.$inquiry->id.' class="edit text-light btn btn-primary btn-sm" style="margin-right: 2px;"><i class="fas fa-print"></i></a>

               <a href='.route('admin.inquiry.edit', $inquiry->id).' name="edit" title="edit" id='.$inquiry->id.' class="edit text-light btn btn-info btn-sm"><i class="far fa-edit"></i></a>

               <a href='.route('admin.inquiry.show', $inquiry->id).'  id='.$inquiry->id.' title="view-details" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;"><i class="far fa-eye"></i></a>';
                    if($inquiry->status=='Confirmed'){

                        $output .= '<a href='.route('admin.driver.assign',$inquiry->id).' id='.$inquiry->id.' title="assign driver & coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Assign</a>';
                        $output .= '<a href='.route('admin.inquiry.sendConfirmMail',$inquiry->id).' id='.$inquiry->id.' title="Send Confirm Mail" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Send Confirm Mail</a>';

                    }elseif($inquiry->status=='Assigned'){

                  $output .= '<a href='.route('admin.driver.assign',$inquiry->id).' id='.$inquiry->id.' title="reassign driver & coach" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">ReAssign</a>';
                    }elseif($inquiry->status=='Quotation'){

                     $output .= '<a href='.route('admin.inquiry.conform',$inquiry->id).' id='.$inquiry->id.' title="Confirm Inquiry" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Confirm</a>';
                     $output .= '<a href='.route('admin.inquiry.SendEmailEnquery',$inquiry->id).' id='.$inquiry->id.' title="Send Mail" class="edit text-light btn btn-primary btn-sm" style="margin-left: 2px;">Send Mail</a>';
                    }
              $output .= '<a  href='.url('admin/delete_enquery', $inquiry->id).'  id='.$inquiry->id.' onclick="return confirm("Are you sure you want to delete this?")" title="view-details" class="edit text-light btn btn-danger btn-sm" style="margin-left: 2px;"><i class="fa fa-trash"></i></a>
               </td></div>';

               

              
       }
   } else {

       $output .= '<tr><td colspan="8">There is no duty available at this date.</td></tr>';
   }

   echo $output;

}






    public function getDutyBydate(Request $request)

    {


                if ($request->ajax()) {

                    
               $duties = DB::table('coach_driver_trip as x')->JOIN('inquiries as i', 'x.trip_id', '=', 'i.id')->JOIN('customers as cr', 'cr.id', '=', 'i.customer_id')->JOIN('users as dv', 'x.driver_id', '=', 'dv.id')->JOIN('coaches as ch', 'x.coach_id', '=', 'ch.id')->JOIN('coach_driver_trip as driver', 'x.trip_id', '=', 'i.id');


                if (!empty($request->date)) {

                $dateselect = date('Y-m-d', strtotime($request->date));

                $duties = $duties->whereDate('i.trip_start_date', '=', $dateselect);

                }

                if (!empty($request->customer_id)) {
                $duties = $duties->where('i.customer_id', $request->customer_id);
                }



               $duties->select('x.id', 'i.quotation_no', 'i.destination', 'i.pick_up_time', 'x.reporting_time', 'x.departure_time', 'i.return_time', 'i.driver_sheet_notes', 'i.trip_start_date', 'ch.registration_no', 'dv.name', 'dv.id as driverID', 'driver.created_at as updatedirver' , 'i.invoice as invoiceId', 'i.id as inqId', 'cr.name as customer_name');
                $duties->orderBy('driver.created_at', 'desc');
                $data = $duties->get()->unique('id');


            


                    return Datatables()->of($data)
                        ->addIndexColumn()
                         ->addColumn('invoice', function($row){
                            $invoice = '<form id="invoiceFormData" action="" method="POST">
                    <div class="form-group">
                     <input type="text" name="inv" value="'.$row->invoiceId.'"  class="invoiceId'.$row->inqId.'"  id="">
                    </div>
                    <div class="form-group">  
                      <input type="button" class="btn btn-primary updateinvoice"  onclick="updateinvoive('.$row->inqId.')" value="Submit">
                    </div>
                  
                    </form>';
                            return $invoice;

                        })
                        ->addColumn('action', function($row){
                            $actionBtn = '<a href="' . URL::route('admin.instructionsheet.view', $row->id) . '" class="btn btn-info btn-sm text-light">Instruction Sheet</a><a href="' . URL::route('admin.instruction.mail', [$row->driverID , $row->id]) . '" class="btn btn-danger btn-sm text-light">Send customer Sheet </a>';
                            return $actionBtn;
                        })
                        ->editColumn('trip_start_date',function($row){
                            $trip_start_date = date('D', strtotime($row->trip_start_date)).', '.date('d-M-y', strtotime($row->trip_start_date) );
                            return $trip_start_date;
                        })
                         ->editColumn('reporting_time',function($row){
                            $reporting_time = date('H:i', strtotime($row->reporting_time) );
                            return $reporting_time;
                        })
                         ->editColumn('pick_up_time',function($row){
                            $pick_up_time = date('H:i', strtotime($row->pick_up_time) );
                            return $pick_up_time;
                        })
                         ->editColumn('return_time',function($row){
                            $return_time = date('H:i', strtotime($row->return_time) );
                            return $return_time;
                        })
                        ->rawColumns(['invoice', 'action'])
                        ->make(true);
                }
        

       

    }







    /*

    **get all conferm trip with avalable driver

    */

    public function fechTripAndDriver($id)

    {
        $check = Inquiry::where('id', $id)->where('status', '=', 'Confirmed')->count();

        $details = Inquiry::where('id', $id)->where('status', '=', 'Confirmed')->first();

        $recheck = Inquiry::where('id', $id)->where('status', '=', 'Assigned')->count();

        if ($check) {

            $trips = DB::table('coach_types_inquiries as CI')
                ->join('inquiries as i', 'CI.inquiry_id', '=', 'i.id')
                ->join('coach_types as ct', 'CI.coach_type_id', '=', 'ct.id')
                ->where('CI.inquiry_id', '=', $id)
                ->select('i.id as inq_id', 'i.pick_up_point', 'i.destination', 'i.pick_up_time', 'i.trip_start_date', 'ct.id as coachTypeId', 'ct.type', 'CI.no_of_coach')
                ->get();

            $coachesList = Coach::where('status', '=', 'Available')

                ->select('id', 'registration_no', 'coach_name')

                ->get()->toArray();

            $coaches = [];
            $drivers = [];

            if($details->is_school && $details->job_end_time && $details->pick_up_time) {
                
                $trip_end_time = $details->school_close_date.' '.$details->job_end_time;
                $trip_ending_time = strtotime($trip_end_time);
                $reportingtime = $details->school_start_date.' '.$details->report_time;
                $pickuptime = $details->school_start_date.' '.$details->pick_up_time;

                $startTime = strtotime($reportingtime);

                $results = DB::select( DB::raw("SELECT bookings.user_id as user_id FROM role_user INNER JOIN bookings on role_user.user_id = bookings.user_id and bookings.trip_end_timstamp >= ".$startTime." and bookings.booking_start <= ".$trip_ending_time." WHERE role_user.role_id = 3") );
                $allUserList = [];
                    
                foreach ($results as $key => $value) {
                    $allUserList[] = $results[$key]->user_id;    
                }

                    
                $drivers = DB::table("role_user")
                            ->join("users", 'users.id', '=', 'role_user.user_id')
                            ->whereNotIn('role_user.user_id', $allUserList)
                            ->where('role_user.role_id', '=', 3)
                            ->select('role_user.user_id as user_id', 'users.name as name', 'users.email as email')
                            ->get();


                $coachresults = DB::select( DB::raw("SELECT bookings.coach_id as coach_id FROM coaches INNER JOIN bookings on coaches.id = bookings.coach_id and bookings.trip_end_timstamp >= ".$startTime." and bookings.booking_start <= ".$trip_ending_time) );
                

                $allCoachList = [];
                
                foreach ($coachresults as $key => $value) {
                  
                    $allCoachList[] = $coachresults[$key]->coach_id;    
                }

                // echo "<pre>"; print_r($allCoachList); die("check");

                $coaches = DB::table("coaches")
                            ->whereNotIn('id', $allCoachList)->get(); 
                

            }

            // echo "<pre>"; print_r($details); die("check");

            return view('admin.template.Inquiry.assign_driver', compact('drivers', 'coaches', 'trips', 'coachesList', 'details'));


        }elseif($recheck){
            $details = Inquiry::where('id', $id)->where('status', '=', 'Assigned')->first();
            $trips = DB::table('coach_types_inquiries as CI')
            ->join('inquiries as i', 'CI.inquiry_id', '=', 'i.id')
            ->join('coach_types as ct', 'CI.coach_type_id', '=', 'ct.id')
            ->where('CI.inquiry_id', '=', $id)
            ->select('i.id as inq_id', 'i.pick_up_point', 'i.destination', 'i.pick_up_time', 'i.trip_start_date', 'ct.id as coachTypeId', 'ct.type', 'CI.no_of_coach')
            ->get();



        $coachesList = Coach::where('status', '=', 'Available')

            ->select('id', 'registration_no', 'coach_name')

            ->get()->toArray();

        $coaches = [];
        $drivers = [];

        if($details->is_school && $details->job_end_time && $details->pick_up_time) {
            
            $trip_end_time = $details->school_close_date.' '.$details->job_end_time;
            $trip_ending_time = strtotime($trip_end_time);
            $reportingtime = $details->school_start_date.' '.$details->report_time;
            $pickuptime = $details->school_start_date.' '.$details->pick_up_time;

            $startTime = strtotime($reportingtime);

            $results = DB::select( DB::raw("SELECT bookings.user_id as user_id FROM role_user INNER JOIN bookings on role_user.user_id = bookings.user_id and bookings.trip_end_timstamp >= ".$startTime." and bookings.booking_start <= ".$trip_ending_time." WHERE role_user.role_id = 3") );
            $allUserList = [];
                
            foreach ($results as $key => $value) {
                $allUserList[] = $results[$key]->user_id;    
            }

                
            $drivers = DB::table("role_user")
                        ->join("users", 'users.id', '=', 'role_user.user_id')
                        ->whereNotIn('role_user.user_id', $allUserList)
                        ->where('role_user.role_id', '=', 3)
                        ->select('role_user.user_id as user_id', 'users.name as name', 'users.email as email')
                        ->get();


            $coachresults = DB::select( DB::raw("SELECT bookings.coach_id as coach_id FROM coaches INNER JOIN bookings on coaches.id = bookings.coach_id and bookings.trip_end_timstamp >= ".$startTime." and bookings.booking_start <= ".$trip_ending_time) );
            

            $allCoachList = [];
            
            foreach ($coachresults as $key => $value) {
              
                $allCoachList[] = $coachresults[$key]->coach_id;    
            }

            $data = DB::table('coach_driver_trip as x')

                ->where('x.trip_id', '=', $id)

                ->get();

                $coach_id= $data[0]->coach_id;
               

               //$coach_id = $data['coach_id'];

            //SELECT * FROM `coach_driver_trip` WHERE `trip_id` = 1944

            // echo "<pre>"; print_r($allCoachList); die("check");

            $coaches = DB::table("coaches")->where('id', $coach_id)->get(); 
            

        }

      

        return view('admin.template.Inquiry.assign_driver', compact('drivers', 'coaches', 'trips', 'coachesList', 'details'));


        } else {

            session()->flash("info", "This Inquiry not confirmed.");

            return redirect()->back();
        }
    }





       public function getdriverlist(Request $request)
    {



        $startTime = strtotime($request->strtingTime);

        $endTime = $request->trip_end_time;


        $results = DB::select(DB::raw("SELECT bookings.user_id as user_id FROM role_user INNER JOIN bookings on role_user.user_id = bookings.user_id and bookings.trip_end_timstamp >= " . $startTime . " and bookings.booking_start <= " . $endTime . " WHERE role_user.role_id = 3"));

         $asigndriver = DB::select(DB::raw("SELECT bookings.user_id as user_id FROM role_user INNER JOIN bookings on role_user.user_id = bookings.user_id and bookings.trip_end_timstamp >= " . $startTime . " and bookings.booking_start <= " . $endTime . " WHERE role_user.role_id = 3 ORDER BY bookings.updated_at DESC LIMIT 1 "));



        $allUserList = [];


        foreach ($results as $key => $value) {

            if($asigndriver[0]->user_id == $results[$key]->user_id){
                
                 $allUserList1[] = $results[$key]->user_id;
                 $allUserList = $allUserList1;

            }

           
        }




        $userData = DB::table("role_user")

            ->join("users", 'users.id', '=', 'role_user.user_id')

            ->whereNotIn('role_user.user_id', $allUserList)

            ->where('role_user.role_id', '=', 3)

            ->select('role_user.user_id as user_id', 'users.name as name', 'users.email as email')

            ->get();



        echo json_encode($userData);

     

        die;






    }



    public function coachlistlist(Request $request)
    {

        $startTime = strtotime($request->strtingTime);

        // $endTime = strtotime($request->endingTime);

        $endTime = $request->trip_end_time;

    
        $results = DB::select(DB::raw("SELECT bookings.coach_id as coach_id FROM coaches INNER JOIN bookings on coaches.id = bookings.coach_id and bookings.trip_end_timstamp >= " . $startTime . " and bookings.booking_start <= " . $endTime));


        $asigncochlist = DB::select(DB::raw("SELECT bookings.coach_id as coach_id FROM coaches INNER JOIN bookings on coaches.id = bookings.coach_id and bookings.trip_end_timstamp >= " .$startTime . " and bookings.booking_start <= " . $endTime. " ORDER BY bookings.updated_at DESC LIMIT 1"));



        $allCoachList = array();

        foreach ($results as $key => $value) {

            if($asigncochlist[0]->coach_id == $results[$key]->coach_id){

            $allCoachList1[] = $results[$key]->coach_id;
            $allCoachList = $allCoachList1;

            }
        }


        $coachData = DB::table("coaches")->whereNotIn('id', $allCoachList)->get();

        echo json_encode($coachData);

        die;
    }




    public function assignDriver(Request $request)

    {


        $validation = Validator::make($request->all(), [

            'driver' => 'required',

            'trip'   => 'required',

            'coach'  => 'required',

            'reporting_time'  => 'required',

            'departure_time'  => 'required',

        ]);



        if ($validation->fails()) {

            //get all errors

            $error = $validation->errors();

            return response()->json(['error' => $error]);
        }
        
        

        $reportingTimeArr = explode(' ', $request->get('reporting_time'));
        $departureTimeArr = explode(' ', $request->get('departure_time'));
        $repdatetime = new DateTime($reportingTimeArr[0]);
        $deodatetime = new DateTime($departureTimeArr[1]);
        $repdatetime_arry[0] = $repdatetime->format('H:i:s');
        $depdatetime_arry[0] = $deodatetime->format('H:i:s');
        $tripdate = $repdatetime->format('Y-m-d');
        $reporting_time   = $repdatetime_arry[0];
        $departure_time   = $depdatetime_arry[0];
        $driver           = $request->get('driver');
        $trip             = $request->get('trip');
        $coach            = $request->get('coach');
        $reportingTimestamp = strtotime($request->get('reporting_time'));
        $departureTimestamp = strtotime($request->get('departure_time'));
        $data  = array();
        $count = count($request->get('driver'));

        
            $drivertrips = Booking::where('trip_id', $trip)->get();

            $Driver_assign_trip = DriverTrip::where('trip_id',  $trip)->get();


            if(count($drivertrips) != 0){

                 Booking::where('trip_id', $trip)->delete();

                DriverTrip::where('trip_id',  $trip)->delete();
            }
        
   
       

        for ($i = 0; $i < $count; $i++) {

            $data = array(

                'driver_id' => $driver[$i],

                'trip_id'   => $trip,

                'coach_id'  => $coach[$i],

                'reporting_time' => $repdatetime_arry[0],

                'departure_time' => $depdatetime_arry[0],


            );



             $data_driver_2  = array('driver_booking_status' => 'Available');
                   
          

            foreach($drivertrips as $drivertrip ){

            if( ($drivertrip['user_id'] != $driver[$i] ) && ($drivertrip['coach_id'] != $coach[$i]) ){

              $user_dirver_id = $drivertrip['user_id'];
              $user_coch_id = $drivertrip['coach_id'];
                $driver_update = DB::table('role_user')

                    ->where('user_id', '=', $user_dirver_id)

                    ->where('role_id', '=', 3)

                    ->update($data_driver_2);

                $data_coach   =  array('status' => 'Available');
                $coach_update =  Coach::where('id', '=', $user_coch_id)->update($data_coach);
              }
              
              

            }
            
              

  

                 $is_save = DriverTrip::insert($data);

                if ($is_save) {

                $data_driver  = array('driver_booking_status' => 'Booked');

                $driver_update = DB::table('role_user')

                    ->where('user_id', '=', $driver[$i])

                    ->where('role_id', '=', 3)

                    ->update($data_driver);


                $data_coach   =  array('status' => 'Booked');

                $coach_update =  Coach::where('id', '=', $coach[$i])->update($data_coach);

                
                $bookingData = [

                    'user_id' => $driver[$i],

                    'coach_id' => $coach[$i],

                    'trip_id' => $trip,

                    'booking_start' => $reportingTimestamp,

                    'booking_end' => $departureTimestamp,

                    'trip_end_timstamp' => $request->trip_end_timestamp

                ];

                $addbooking = new Booking;

                $addbooking->create($bookingData);

            }







        }



        $data_trip   = array('status' => 'Assigned');

        $trip_update = Inquiry::where('id', '=', $trip)->update($data_trip);



        session()->flash("success", "Driver assigned with trip Successfully");

        $error = array('msg' => 'Driver assigned with trip Successfully');

         $urlsend = url('admin/inquiry?trip-date='.$tripdate.'');


        return response()->json(['success' => $error, 'view_url' => $urlsend ]);

    }


    /*

    get trip details for assign driver

    */

    public function getTrip($id)

    {

        $inquiries = DB::table('inquiries as i')

            ->join('coach_types_inquiries as coch_inq', 'i.id', '=', 'coch_inq.inquiry_id')

            ->join('coach_types as ct', 'coch_inq.coach_type_id', '=', 'ct.id')

            ->select('i.pick_up_point', 'i.destination', 'i.trip_start_date', 'i.id', 'ct.type as coachType', 'ct.id as coachTypeId', 'coch_inq.no_of_coach')

            ->where('i.id', '=', $id)

            ->get()->toArray();

        return $inquiries;
    }



    /*

    **get available coaches and driver

    */

    public function getCoaches($id)

    {

        $availableCoach = Coach::where('coach_type', '=', $id)->where('booking_status', '=', 'Available')->pluck("coach_name", "id");

        $availableDriver = DB::table('users')

            ->join('role_user', 'users.id', '=', 'role_user.id')

            ->where('role_user.role_id', '=', 3)

            ->where('users.status', '=', 1)

            ->where('role_user.driver_booking_status', '=', 'Available')

            ->pluck("users.name", "users.id");



        return response()->json(['availableCoach' => $availableCoach, 'availableDriver' => $availableDriver]);
    }



    /**

     * one click to conform the inquiry.

     *

     * @param  int inquiry id $id

     * @return \Illuminate\Http\Response

     */

    public function confirmInquiry($id)

    {

        $inquiry  = Inquiry::find($id);

        $inquiry->status = 'Confirmed';

        $is_update = $inquiry->save();

        if ($is_update) {

            $customer = Customer::find($inquiry->customer_id);

            $customer->status = 1;

            $customer->save();



            //-----mail -------

            $getCoach = DB::table('coach_types as ct')

                ->join('coach_types_inquiries as ct_inq', 'ct.id', '=', 'ct_inq.coach_type_id')

                ->where('ct_inq.inquiry_id', '=', $id)

                ->select('ct.type', 'ct_inq.no_of_coach', 'ct_inq.cost_per_mile', 'ct_inq.no_of_days', 'ct_inq.cost_per_day', 'ct_inq.total_charge as cost_per_coach')

                ->get()->toArray();



            $getdata = DB::table('inquiries as i')

                ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

                ->where('i.id', $id)

                ->select('cr.name as cr_name', 'cr.email as cr_email', 'cr.phone as cr_phone', 'cr.address as cr_address', 'i.*')

                ->first();





            $data = array(

                'name'               => $getdata->cr_name,

                'email'              => $getdata->cr_email,

                'phone'              => $getdata->cr_phone,

                'address'            => $getdata->cr_address,

                'quotation_no'       => $getdata->quotation_no,

                'pick_up_point'      => $getdata->pick_up_point,

                'pick_up_time'       => $getdata->pick_up_time ?? '',

                'destination'        => $getdata->destination,

                'trip_start_date'    => date('D d-m-Y', strtotime($getdata->trip_start_date)),

                'return_date'        => empty($getdata->is_one_way) ? date('D d-m-Y', strtotime($getdata->return_date)) : 'One Way',



                'deposit_required'   => $getdata->deposit_required,

                'deposit_received'   => $getdata->deposit_received,

                'balance_outstanding' => $getdata->balance_outstanding,

                'total_charge'       => round($getdata->total_charge),

                'no_of_passengers'   => $getdata->no_of_passengers,

                'no_of_wheelchairs'  => $getdata->no_of_wheelchairs,

                'booking_date'       => $getdata->booking_date,

                'driver_sheet_notes' => $getdata->driver_sheet_notes,

                'coach_detail'       => $getCoach,

            );



            $email =  trim($getdata->cr_email);

            // $send =  Mail::to($email)->send(new SendConfirmation($data));

            //------/mail----

            session()->flash("success", "Inquiry has been updated successfully.");

            return redirect()->back();
        }
    }



    /**

     * download driver instruction sheet PDF

     *

     */


    public function driverInstructionSheetDownload($id)
    {



      $data = DB::table('coach_driver_trip as x')

            ->join('users as u', 'u.id', '=', 'x.driver_id')

            ->join('inquiries as i', 'i.id', '=', 'x.trip_id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('coaches as c', 'c.id', '=', 'x.coach_id')

            ->where('x.id', $id)

            ->select('x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.id', 'i.quotation_no', 'i.pick_up_time','i.return_time', 'i.destination', 'i.trip_start_date', 'i.total_charge', 'i.balance_outstanding',  'i.deposit_received', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name')

            ->first();

        $pdf = PDF::loadView('pdf.driver_instruction_sheet', compact('data'));

        $randName = 'sheet' . rand(0, 999999) . '.pdf';

        return $pdf->download($randName);
    }



    /**

     * view driver instruction sheet and print

     *

     */

    public function driverInstructionSendMail($id)
    {
             $data = DB::table('coach_driver_trip as x')

            ->join('users as u', 'u.id', '=', 'x.driver_id')

            ->join('inquiries as i', 'i.id', '=', 'x.trip_id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('coaches as c', 'c.id', '=', 'x.coach_id')

            ->where('x.id', $id)

            ->select('x.reporting_time','x.driver_id', 'x.departure_time', 'i.pick_up_point', 'i.id', 'i.quotation_no', 'i.pick_up_time','i.return_time', 'i.destination', 'i.no_of_passengers', 'i.trip_start_date', 'i.total_charge', 'i.balance_outstanding',  'i.deposit_received', 'i.deposit_required', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name', 'u.email as driver_email')

            ->first();
        // echo "<pre>";print_r($data);die;
        $coach_driver_trip_id = $id;

        $mailData['data'] = $data;

        $mailData['coach_driver_trip_id'] = $id;

        $user = array('driver_email' => $data->driver_email);
        $flag = Mail::send('instruction_sheet_mail', $mailData, function ($message) use ($user) {

            $message->to($user['driver_email'])->subject('Driver Confirmation Sheet');

            $message->from(env('MAIL_FROM_ADDRESS'));
        });



        session()->flash("success", "mail Send  Successfully to Driver !");

        return redirect()->back();

    }
    public function driverInstructionSheetView($id)
    {



        $data = DB::table('coach_driver_trip as x')

            ->join('users as u', 'u.id', '=', 'x.driver_id')

            ->join('inquiries as i', 'i.id', '=', 'x.trip_id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('coaches as c', 'c.id', '=', 'x.coach_id')

            ->where('x.id', $id)

            ->select('x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.id', 'i.quotation_no', 'i.pick_up_time','i.return_time', 'i.no_of_passengers', 'i.destination', 'i.trip_start_date', 'i.total_charge', 'i.balance_outstanding',  'i.deposit_received', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name')

            ->first();

        $coach_driver_trip_id = $id;

        return view('admin.template.Inquiry.instruction_sheet', compact('data', 'coach_driver_trip_id'));
    }



    public function editDriverInstructionSheet($id)
    {



        $data = DB::table('coach_driver_trip as x')

            ->join('users as u', 'u.id', '=', 'x.driver_id')

            ->join('inquiries as i', 'i.id', '=', 'x.trip_id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('coaches as c', 'c.id', '=', 'x.coach_id')

            ->where('x.id', $id)

            ->select('x.id as coach_driver_trip_id', 'x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.id as inquiry_id', 'i.no_of_passengers', 'i.customer_id', 'i.pick_up_time', 'i.destination', 'i.trip_start_date', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name', 'u.id as driver_id')

            ->first();



        $drivers = DB::table('users as u')

            ->join('role_user as ru', 'ru.user_id', '=', 'u.id')

            ->where('ru.role_id', '=', 3)

            ->where('ru.driver_booking_status', '=', 'Available')

            ->where('u.status', '=', 1)

            ->select('u.id', 'u.name', 'u.email')

            ->get();



        return view('admin.template.Inquiry.edit_instruction_sheet', compact('data', 'drivers'));
    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function updateDriverSheet(Request $request, $id)

    {



        // echo "<pre>";

        // print_r($request->all());

        // die("Df");





        $validation = Validator::make($request->all(), [

            'trip_start_date'    => 'required',

            'driver'             => 'required',

            'customer_name'      => 'required',

            'phone'              => 'required',

            'pick_up_point'      => 'required',

            'pick_up_time'       => 'required',

            'email'              => 'required',

            'address'            => 'required',

            'destination'        => 'required',

            'departure_time'     => 'required',

            'driver_sheet_notes' => 'required',

            'customer_id'        => 'required',

            'inquiry_id'         => 'required',

            'old_driver_id'      => 'required'



        ])->validate();









        $data_inquiry = array(

            'trip_start_date' => $request->get('trip_start_date'),

            'pick_up_point' => $request->get('pick_up_point'),

            'pick_up_time' => $request->get('pick_up_time'),

            'destination' => $request->get('destination'),

            'driver_sheet_notes' => $request->get('driver_sheet_notes'),

        );

        $is_update = Inquiry::where('id', '=', $request->get('inquiry_id'))->update($data_inquiry);





        if ($is_update) {

            if ($request->get('old_driver_id') != $request->get('driver')) {

                $is_update = DB::table('role_user')->where('user_id', '=', $request->get('old_driver_id'))->update(['driver_booking_status' => 'Available']);

                $is_update = DB::table('role_user')->where('user_id', '=', $request->get('driver'))->update(['driver_booking_status' => 'Booked']);
            }

            $data = array(

                'driver_id' => $request->get('driver'),

                'departure_time' => $request->get('departure_time'),

            );

            $is_update = DB::table('coach_driver_trip')->where('id', '=', $id)->update($data);



            $data_customer = array(

                'name' => $request->get('customer_name'),

                'phone' => $request->get('phone'),

                'email' => $request->get('phone'),

            );

            $customer_update = Customer::where('id', '=', $request->get('customer_id'))->update($data_customer);



            if ($customer_update) {

                session()->flash("success", "Driver Instruction Sheet has been updated");

                return redirect(route('admin.instructionsheet.view', $id));
            }

            session()->flash("error", "Something went wrong!");

            return redirect(route('admin.instructionsheet.view', $id));
        }
    }





    /**

     * get cost according to coach type selected

     *

     * @param  int coach type id 

     * @return \Illuminate\Http\Response

     */

    public function getCost($id)

    {

        $CoachType  = CoachType::where('id', $id)->first();



        $data = array(

            'cost_per_mile' => $CoachType->cost_per_mile,

            'cost_per_day' => $CoachType->cost_per_day,

            'cost_per_driver' => $CoachType->cost_per_driver,

        );



        return response()->json($data);
    }





    /**

     * store Purchase Order

     *

     * @param  

     * @return \Illuminate\Http\Response

     */

    public function purchaseOrderStore(Request $request)

    {

        $validation = Validator::make($request->all(), [

            'date'          =>    'required|date',

            'order_no'      =>    'required|string|max:255|unique:purchase_order,order_no',



            'supplier_id'   =>    'nullable|string|max:255',

            'supplier'      =>    'nullable|string|max:255',

            'supplier_address'    => 'nullable|string',

            'supplier_contact_number'  => 'nullable|string|max:255',

            'supplier_email'   => 'nullable|email|max:255',



            'ordered_by'     => 'required|string|max:255',

            'subtotal'       => 'required|string|max:255',

            'carriage'       => 'nullable|string|max:255',

            'total_exc_vat'  => 'required|string|max:255',

            'payment_method' => 'nullable|string|max:255',

            'vat'            => 'required|string|max:255',

            'total_inc_vac'  => 'required|string|max:255',

            'special_instuctions' => 'nullable|string|max:255',



            'quantity'       => 'required|max:255',

            'description'    => 'required|max:255',

            'vehicle_no'     => 'required|max:255',

            'unit_price'     => 'required|max:255',

            'extended_price' => 'required|max:255',

        ]);



        if ($validation->fails()) {

            //get all errors

            $error = $validation->errors();

            return response()->json(['error' => $error]);
        }

        if ((!empty(request('supplier_id'))) || (!empty(request('supplier')) && !empty(request('supplier_address')) && !empty(request('supplier_contact_number')) && !empty(request('supplier_email')))) {

            $supplier_id = request('supplier_id') ?? '';

            if (!empty(request('supplier_email'))) {

                $supplier_data = array(

                    'name'      => $request->get('supplier'),

                    'address'   => $request->get('supplier_address'),

                    'phone'     => $request->get('supplier_contact_number'),

                    'email'     => $request->get('supplier_email') ?? NULL,

                );

                $supplier_id  =  Supplier::insertGetId($supplier_data);
            }



            $data = array(

                'order_no'           => $request->get('order_no'),

                'date'               => $request->get('date'),

                'supplier_id'        => $supplier_id,

                'ordered_by'         => $request->get('ordered_by'),

                'subtotal'           => $request->get('subtotal'),

                'carriage'           => $request->get('carriage'),

                'total_exc_vat'      => $request->get('total_exc_vat'),

                'payment_method'     => $request->get('payment_method') ?? NULL,

                'vat'                => $request->get('vat'),

                'total_inc_vac'      => $request->get('total_inc_vac'),

                'special_instuctions' => $request->get('special_instuctions'),

            );



            $order = PurchaseOrder::insertGetId($data);



            $count = count($request->get('description')); //get array lenth

            $data = array();

            $quantity       = $request->get('quantity');

            $description    = $request->get('description');

            $vehicle_no     = $request->get('vehicle_no');

            $unit_price     = $request->get('unit_price');

            $extended_price = $request->get('extended_price');



            for ($i = 0; $i < $count; $i++) {

                if ($description[$i] != NULL) {

                    $new = array(

                        'order_id'       => $order,

                        'quantity'       => $quantity[$i],

                        'description'    => $description[$i],

                        'vehicle_no'     => $vehicle_no[$i],

                        'unit_price'     => $unit_price[$i],

                        'extended_price' => $extended_price[$i],

                    );



                    array_push($data, $new);

                    $new = [];
                }
            }

            $is_save_item = OrderItem::insert($data);



            $success = array('msg' => 'Order Created Successfully.');

            return response()->json(['success' => $success]);

            exit();
        } else {

            $failed = array('msg' => 'Choose Supplier or fill Supplier details');

            return response()->json(['error' => $failed]);

            exit();
        }
    }



    /**

     *  Purchase Order

     *

     * @param  

     * @return \Illuminate\Http\Response

     */

    public function purchaseOrderindex()

    {

        $orders = DB::table('purchase_order as od')

            ->join('suppliers as sp', 'sp.id', '=', 'od.supplier_id')

            ->orderBy('od.id', 'desc')

            ->select('od.*', 'sp.name')

            ->get();

        return view('admin.template.Inquiry.purchase_order_list', compact('orders'));
    }



    /**

     * get order details 

     *

     * @param  int $id as purches order id

     * @return \Illuminate\Http\Response

     */

    public function purchaseOrderView($id)

    {

        $order = DB::table('purchase_order as po')

            ->join('suppliers as sp', 'po.supplier_id', '=', 'sp.id')

            ->join('order_items as oi', 'po.id', '=', 'oi.order_id')

            ->where('po.id', $id)

            ->get()->toArray();

        return view('admin.template.Inquiry.purchase_order_detail', compact('order'));
    }



    /**

     * create unique and serial wise purches order  

     *

     * @param 

     * @return \Illuminate\Http\Response

     */

    public function newPurchaseOrderNo()

    {

        $max_order = PurchaseOrder::max('order_no');

        if ($max_order) {

            $max_order = $max_order + 1;

            return $max_order;

            exit();
        } else {

            $max_order = 100;

            return $max_order;
        }
    }



    /**

     * create new purchase order  

     *

     * @param 

     * @return \Illuminate\Http\Response

     */

    public function purchaseOrderCreate()

    {

        $newOrderNo = $this->newPurchaseOrderNo();

        $suppliers = Supplier::where('status', 'AC')->get();

        return view('admin.template.Inquiry.purchase_order', compact('newOrderNo', 'suppliers'));
    }



    /**

     * get supplier details  

     *

     * @param 

     * @return \Illuminate\Http\Response

     */

    public function supplierDetail()

    {

        $suppliers = Supplier::all();



        return view('admin.template.Inquiry.supplier-details', compact('suppliers'));
    }





    /**

     * trip import  view form

     *

     * @param 

     * @return \Illuminate\Http\Response

     */

    public function import($id = false)

    {



        if ($id) {

            $status = $id;
        } else {

            $status = 0;
        }


        return view('admin.template.Inquiry.trip_import', compact('status'));
    }





    /**

     * trip import  save

     *

     * @param 

     * @return \Illuminate\Http\Response

     */

    public function saveImport(Request $request)

    {

         //echo "<pre>"; print_r($request->all()); die("check");

        $_SESSION['is_school'] = $request->is_school;

     

        $is_save = Excel::import(new TripImport, request()->file('file'));

        //$is_save = Excel::import(new CustomerImport,request()->file('file'));


        $data = ImportCsv::first();

        if($data){
            ImportCsv::where('id', $data->id)->delete();
           // $message = "Success! ".$data->success." Trip imported successfully  and ".$data->failed." Trip failed ";
           $message = "Success!  Trip imported successfully ";
            session()->flash("success", $message);
        }else
        {
            session()->flash("info","Something went wrong!");
        }
        return redirect()->back();
    }



    /**

     * clone the existing enquiry.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function cloneInquiry($id)

    {

        $inquiry = DB::table('inquiries as i')

            ->where('i.id', '=', $id)

            ->first();

        $coach_types = CoachType::where('status', '1')->get();

        $customers = Customer::all();



        $coaches = DB::table('coach_types_inquiries as coch_inq')

            ->join('coach_types as ct', 'coch_inq.coach_type_id', '=', 'ct.id')

            ->where('coch_inq.inquiry_id', '=', $id)

            ->select('coch_inq.*', 'ct.type', 'ct.cost_per_mile', 'ct.cost_per_day', 'ct.cost_per_driver')

            ->get();

        //dd($inquiry);



        return view('admin.template.Inquiry.clone_inquiry', compact('inquiry', 'coaches', 'customers', 'coach_types'));
    }





    public function delete_Inquery($id = '')

    {



        $in  = CoachTypesInquiries::where('inquiry_id', $id)->delete();

        $check_delete = Inquiry::find($id)->delete();



        if ($check_delete) {

            session()->flash("success", "Delete Successfully!");

            return redirect()->back();
        } else {



            session()->flash('error', 'Invalid Enquiry!');

            return redirect()->back();

            
        }
        
        exit();
    }





    public function SendEmailEnquery($id = '')

    {



        $getdata = DB::table('inquiries as i')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->where('i.id', $id)

            ->select('cr.name as cr_name', 'cr.email as cr_email', 'cr.phone as cr_phone', 'cr.address as cr_address', 'i.*')

            ->first();

        $user = User::find($getdata->quated_by);



        $getCoach = DB::table('coach_types as ct')

            ->join('coach_types_inquiries as ct_inq', 'ct.id', '=', 'ct_inq.coach_type_id')

            ->where('ct_inq.inquiry_id', '=', $id)

            ->select('ct.type', 'ct_inq.no_of_coach', 'ct_inq.cost_per_mile', 'ct_inq.no_of_days', 'ct_inq.cost_per_day', 'ct_inq.total_charge as cost_per_coach')

            ->get()->toArray();





        $data = array(

            'name'               => $getdata->cr_name,

            'email'              => $getdata->cr_email,

            'phone'              => $getdata->cr_phone,

            'address'            => $getdata->cr_address,

            'quotation_no'       => $getdata->quotation_no,

            'pick_up_point'      => $getdata->pick_up_point,

            'pick_up_time'       => $getdata->pick_up_time ?? '',

            'destination'        => $getdata->destination,

            'return_ctime'        => $getdata->return_time,

            'trip_start_date'    => date('D d-m-Y', strtotime($getdata->trip_start_date)),

            'return_date'        => empty($getdata->is_one_way) ? date('D d-m-Y', strtotime($getdata->return_date)) : 'One Way',

            'return_time'        => empty($getdata->is_one_way) ? date('D d-m-Y', strtotime($getdata->return_date)) : '',

            'quated_by'         => $user->name,

            'deposit_required'   => $getdata->deposit_required,

            'deposit_received'   => $getdata->deposit_received,

            'balance_outstanding' => $getdata->balance_outstanding,

            'total_charge'       => $getdata->total_charge,

            'no_of_passengers'   => $getdata->no_of_passengers,

            'no_of_wheelchairs'  => $getdata->no_of_wheelchairs,

            'booking_date'       => $getdata->booking_date,

            'driver_sheet_notes' => $getdata->driver_sheet_notes,

            'coach_detail'       => $getCoach,

        );









        $email =  trim($getdata->cr_email);



        $send =  Mail::to($email)->send(new SendQuotation($data));



        session()->flash("success", "Mail! Sent successfully");

        return redirect()->back();
    }



    public function sendConfirmMail($id = '')

    {

        $getdata = DB::table('inquiries as i')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->where('i.id', $id)

            ->select('cr.name as cr_name', 'cr.email as cr_email', 'cr.phone as cr_phone', 'cr.address as cr_address', 'i.*')

            ->first();

        $getCoach = DB::table('coach_types as ct')

            ->join('coach_types_inquiries as ct_inq', 'ct.id', '=', 'ct_inq.coach_type_id')

            ->where('ct_inq.inquiry_id', '=', $id)

            ->select('ct.type', 'ct_inq.no_of_coach', 'ct_inq.cost_per_mile', 'ct_inq.no_of_days', 'ct_inq.cost_per_day', 'ct_inq.total_charge as cost_per_coach')

            ->get()->toArray();



        $data = array(

            'name'               => $getdata->cr_name,

            'email'              => $getdata->cr_email,

            'phone'              => $getdata->cr_phone,

            'address'            => $getdata->cr_address,

            'quotation_no'       => $getdata->quotation_no,

            'pick_up_point'      => $getdata->pick_up_point,

            'pick_up_time'       => $getdata->pick_up_time ?? '',

            'destination'        => $getdata->destination,

            'return_ctime'        => $getdata->return_time,

            'trip_start_date'    => date('D d-m-Y', strtotime($getdata->trip_start_date)),

            'return_date'        => empty($getdata->is_one_way) ? date('D d-m-Y', strtotime($getdata->return_date)) : 'One Way',



            'deposit_required'   => $getdata->deposit_required,

            'deposit_received'   => $getdata->deposit_received,

            'balance_outstanding' => $getdata->balance_outstanding,

            'total_charge'       => $getdata->total_charge,

            'no_of_passengers'   => $getdata->no_of_passengers,

            'no_of_wheelchairs'  => $getdata->no_of_wheelchairs,

            'booking_date'       => $getdata->booking_date,

            'driver_sheet_notes' => $getdata->driver_sheet_notes,

            'coach_detail'       => $getCoach,

        );



        $email =  trim($getdata->cr_email);





        $send =  Mail::to($email)->send(new SendConfirmation($data));







        session()->flash("success", "Mail! Sent successfully");

        return redirect()->back();
    }



    public function deleteCustomeRecord(Request $request)

    {



        $ids = $request->get('id');

        if ($ids) {



            foreach ($ids as $key => $id) {

                Inquiry::find($id)->delete();
            }



            session()->flash("success", "Records ! Deleted successfully");
        } else {

            session()->flash("success", "Please select records to delete");
        }



        return redirect()->back();
    }
}
