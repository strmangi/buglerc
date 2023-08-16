<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Inquiry;

use App\Models\CoachType;
use App\Models\ImportCsv;
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

use App\Mail\SendQuotation;

use App\Mail\Quotation;

use App\Mail\SendConfirmation;



use App\Imports\TripImport;

use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;



class InquiryController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {



        $inquiries = DB::table('inquiries as i')

            ->leftJoin('customers as cr', 'i.customer_id', '=', 'cr.id')
        ->leftJoin('coach_driver_trip as driver', 'i.id', '=', 'driver.trip_id')

            ->select('i.*', 'cr.name as cr_name', 'cr.contact_name as cr_contact_name', 'cr.email as cr_email', 'cr.phone as cr_phone',  'driver.driver_id as driver_id')

            ->orderBy('i.trip_start_date', 'desc')

            //->where('trip_end_date','>=',date('Y-m-d'))

            // ->orderBy('i.updated_at', 'desc')

            ->get();



        // echo "<pre>"; print_r($inquiries); echo "</pre>";

        // die("check");

        return view('admin.template.Inquiry.inquiry', compact('inquiries'));
    }



    public function school()

    {

        // $inquiries = DB::table('inquiries as i')

        //     ->leftJoin('customers as cr', 'i.customer_id', '=', 'cr.id')

        //     ->select('i.*', 'cr.name as cr_name', 'cr.contact_name as cr_contact_name', 'cr.email as cr_email', 'cr.phone as cr_phone')

        //     ->orderBy('i.trip_start_date', 'desc')

        //     ->where('is_school', '1')

        //     ->where('trip_end_date', '>=', date('Y-m-d'))

        //     // ->orderBy('i.updated_at', 'desc')

        //     ->get();

        $inquiries = DB::table('inquiries as i')
        ->leftJoin('customers as cr','i.customer_id', '=', 'cr.id')
                    ->leftJoin('coach_driver_trip as driver', 'i.id', '=', 'driver.trip_id')

        ->select('i.*','cr.name as cr_name','cr.contact_name as cr_contact_name','cr.email as cr_email','cr.phone as cr_phone',  'driver.driver_id as driver_id')
        ->orderBy('i.updated_at', 'desc')
        ->where('is_school','1')
        // ->orderBy('i.updated_at', 'desc')
        ->get();
        

        // echo "<pre>"; print_r($inquiries); die("check");

        return view('admin.template.Inquiry.school_inquiry', compact('inquiries'));
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

        $customers = Customer::all();



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

                    'name'        => $request->get('customer_name') ?? NULL,

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



            // echo "<pre>"; print_r($trip_save); die("check");



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



                    $custome          = Customer::find($customer_id);

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

            'Pickup_time_not_fix'    => 'nullable',

            'Destination'            => 'required|string',

            'no_of_passengers'       => 'required|max:11',

            'coach_type'             => 'required|max:11',

            'status'                 => 'required',

        ])->validate();



        $trip_data = array(

            'customer_id'          => $request->get('customer_id'),

            'pick_up_point'        => $request->get('Pickup_point'),

            'pick_up_time'         => $request->get('Pickup_time'),

            'destination'          => $request->get('Destination'),

            'no_of_passengers'          => $request->get('no_of_passengers'),

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

        $no_of_coaches   = $request->get('no_of_coaches') ??  array();

        $CostPerMile     = $request->get('CostPerMile') ??  0;

        $no_of_days      = $request->get('no_of_days') ??  0;

        $cost_for_day    = $request->get('cost_for_day') ??  0;

        $coach_type      = $request->get('coach_type') ??  0;

        $cost_for_driver    = $request->get('cost_for_driver') ??  0;

        $total_charge_per_coach = $request->get('total_charge_per_coach') ??  0;



        // $count = count($no_of_coaches); //get array lenth

        $data = array();

        // $no_of_coaches = $request->get('no_of_coaches') ?? 0;

        // for($i=0; $i < $count; $i++)

        // {

        //     if($no_of_coaches[$i] != NULL)

        //     {

        //         $new = array(

        //             'coach_type_id' => $coach_type[$i],

        //             'inquiry_id'    => $id,

        //             'no_of_coach' => $no_of_coaches[$i],

        //             'cost_per_mile' => $CostPerMile[$i],

        //             'no_of_days'    => $no_of_days[$i],

        //             'cost_per_day'  => $cost_for_day[$i],

        //             'driver_per_hour_cost'  => $cost_for_driver[$i],

        //             'total_charge'  => $total_charge_per_coach[$i],

        //             );



        //         array_push($data,$new);

        //         $new = [];

        //     }

        // }

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

        do {

            $rndno = rand(111111, 999999);

            $quotation_no = $rndno;
        } while (!empty(Inquiry::where('quotation_no', $quotation_no)->first()));

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

        $duties = DB::table('coach_driver_trip as x')

            ->join('inquiries as i', 'x.trip_id', '=', 'i.id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('users as dv', 'x.driver_id', '=', 'dv.id')

            ->join('coaches as ch', 'x.coach_id', '=', 'ch.id')

            ->select('x.id', 'i.destination', 'x.reporting_time', 'x.departure_time', 'x.slot_time', 'i.trip_start_date', 'ch.registration_no', 'dv.name', 'dv.id as driverID', 'dv.email', 'i.driver_sheet_notes', 'cr.name as customer_name', 'i.id as inqId', 'i.invoice as invoiceId')

            ->orderBy('i.created_at', 'desc')

            ->get();



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

            ->select('x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.pick_up_time', 'i.destination', 'i.trip_start_date', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name')

            ->first();

        $coach_driver_trip_id = $sheetID;

        $mailData['data'] = $data;

        $mailData['coach_driver_trip_id'] = $sheetID;



        $flag = Mail::send('instruction_sheet_mail', $mailData, function ($message) use ($user) {

            //$user->email

            $message->to($user->email)->subject('Driver Instruction Sheet');



            $message->from('Support@Bugler.com', 'Bugler');
        });



        session()->flash("success", "mail Send  Successfully to Driver !");

        return redirect()->back();







        // echo "<pre>";

        // print_r($id);

        // echo "<br>";

        // print_r($sheetID);

        // die("Df");

    }









    public function getDutyBydate()

    {

        $duties = DB::table('coach_driver_trip as x')

            ->join('inquiries as i', 'x.trip_id', '=', 'i.id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('users as dv', 'x.driver_id', '=', 'dv.id')

            ->join('coaches as ch', 'x.coach_id', '=', 'ch.id');

        if (!empty($_POST['duty_date'])) {

            $duties = $duties->whereDate('i.trip_start_date', $_POST['duty_date']);
        }

        if (!empty($_POST['customer_id'])) {

            $duties = $duties->where('i.customer_id', $_POST['customer_id']);
        }



        $duties->select('x.id', 'i.quotation_no', 'i.destination', 'i.pick_up_time', 'x.reporting_time', 'x.departure_time', 'i.driver_sheet_notes', 'i.trip_start_date', 'ch.registration_no', 'dv.name', 'cr.name as customer_name');

        $data = $duties->get();

        //dd($data);

        $output = "";

        if (count($data) > 0) {

            $output = "";

            foreach ($data as $duty) {

                $date = date('D d-M-Y', strtotime($duty->trip_start_date));

                $output .= '<tr>' .

                    '<td>' . $duty->customer_name . '</td>' .

                    '<td>' . $duty->registration_no . '</td>' .

                    '<td>' . $duty->name . '</td>' .

                    '<td>' . $duty->reporting_time . '</td>' .

                    '<td>' . $duty->departure_time . '</td>' .

                    '<td>' . $duty->destination . '</td>' .

                    '<td>' . $duty->driver_sheet_notes . '</td>' .

                    '<td>' . $date . '</td>' .

                    '<td><a href="' . URL::route('admin.instructionsheet.view', $duty->id) . '" class="btn btn-info btn-sm text-light">Instruction Sheet</a></td>';
            }
        } else {

            $output .= '<tr><td colspan="8">There is no duty available at this date.</td></tr>';
        }

        echo $output;
    }







    /*

    **get all conferm trip with avalable driver

    */

    public function fechTripAndDriver($id)

    {
        $check = Inquiry::where('id', $id)->where('status', '=', 'Confirmed')->count();
        $details = Inquiry::where('id', $id)->where('status', '=', 'Confirmed')->first();

        if ($check) {

            // $drivers = DB::table('users as u')
            //     ->join('role_user as ru', 'ru.user_id', '=', 'u.id')
            //     ->where('ru.role_id', '=', 3)
            //     ->where('ru.driver_booking_status', '=', 'Available')
            //     ->where('u.status', '=', 1)
            //     ->select('u.id', 'u.name', 'u.email')
            //     ->get();



            $trips = DB::table('coach_types_inquiries as CI')
                ->join('inquiries as i', 'CI.inquiry_id', '=', 'i.id')
                ->join('coach_types as ct', 'CI.coach_type_id', '=', 'ct.id')
                ->where('CI.inquiry_id', '=', $id)
                ->select('i.id as inq_id', 'i.pick_up_point', 'i.destination', 'i.pick_up_time', 'i.trip_start_date', 'ct.id as coachTypeId', 'ct.type', 'CI.no_of_coach')
                ->get();





            /*$coachesList  = array();

            foreach($trips as $coachType)

            {

               $coaches = Coach::where('status','=','Available')

               ->where('coach_type','=',$coachType->coachTypeId)

               ->select('id','registration_no','coach_name')

               ->get()->toArray();

               $coachesList[$coachType->coachTypeId] = $coaches;

            }*/

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
        } else {

            session()->flash("info", "This Inquiry not confirmed.");

            return redirect()->back();
        }
    }





    public function getdriverlist(Request $request)
    {



        $startTime = strtotime($request->strtingTime);

        // $endTime = strtotime($request->endingTime);

        $endTime = $request->trip_end_time;



        // echo "<pre>"; print_r($request->all()); die("check");



        $results = DB::select(DB::raw("SELECT bookings.user_id as user_id FROM role_user INNER JOIN bookings on role_user.user_id = bookings.user_id and bookings.trip_end_timstamp >= " . $startTime . " and bookings.booking_start <= " . $endTime . " WHERE role_user.role_id = 3"));



        $allUserList = [];



        foreach ($results as $key => $value) {

            $allUserList[] = $results[$key]->user_id;
        }



        $userData = DB::table("role_user")

            ->join("users", 'users.id', '=', 'role_user.user_id')

            ->whereNotIn('role_user.user_id', $allUserList)

            ->where('role_user.role_id', '=', 3)

            ->select('role_user.user_id as user_id', 'users.name as name', 'users.email as email')

            ->get();



        echo json_encode($userData);

        // echo "<pre>"; print_r($userData); die("check");

        die;



        // SELECT bookings.user_id as user_id FROM role_user INNER JOIN bookings on role_user.user_id = bookings.user_id and bookings.booking_end >= 1624032660 and bookings.booking_start <= 1624025460 WHERE role_user.role_id = 3;



    }



    public function coachlistlist(Request $request)
    {

        $startTime = strtotime($request->strtingTime);

        // $endTime = strtotime($request->endingTime);

        $endTime = $request->trip_end_time;

        // echo "StartTime : ".$startTime;

        // echo "<pre>"; print_r($request->all()); die("check");



        $results = DB::select(DB::raw("SELECT bookings.coach_id as coach_id FROM coaches INNER JOIN bookings on coaches.id = bookings.coach_id and bookings.trip_end_timstamp >= " . $startTime . " and bookings.booking_start <= " . $endTime));



        $allCoachList = [];



        foreach ($results as $key => $value) {

            $allCoachList[] = $results[$key]->coach_id;
        }





        $coachData = DB::table("coaches")

            ->whereNotIn('id', $allCoachList)->get();





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



        // echo "<pre>"; print_r($request->all()); echo "</pre>"; die("check");



        $reportingTimeArr = explode(' ', $request->get('reporting_time'));

        $departureTimeArr = explode(' ', $request->get('departure_time'));



        $reporting_time   = $reportingTimeArr[1];

        $departure_time   = $departureTimeArr[1];

        $driver           = $request->get('driver');

        $trip             = $request->get('trip');

        $coach            = $request->get('coach');

        // $slot_time        = $request->get('slot_time');

        $reportingTimestamp = strtotime($request->get('reporting_time'));

        $departureTimestamp = strtotime($request->get('departure_time'));



        $data  = array();

        $count = count($request->get('driver'));



        for ($i = 0; $i < $count; $i++) {

            $data = array(

                'driver_id' => $driver[$i],

                'trip_id'   => $trip,

                'coach_id'  => $coach[$i],

                'reporting_time' => $reporting_time,

                'departure_time' => $departure_time,

                // 'slot_time'      => $slot_time,



            );



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



        session()->flash("success", "Driver assigned with trip Successfully!");



        $error = array('msg' => 'Driver assigned with trip Successfully!');

        return response()->json(['success' => $error]);
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

            ->select('x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.pick_up_time', 'i.destination', 'i.trip_start_date', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name')

            ->first();

        $pdf = PDF::loadView('pdf.driver_instruction_sheet', compact('data'));

        $randName = 'sheet' . rand(0, 999999) . '.pdf';

        return $pdf->download($randName);
    }



    /**

     * view driver instruction sheet and print

     *

     */

    public function driverInstructionSheetView($id)
    {



        $data = DB::table('coach_driver_trip as x')

            ->join('users as u', 'u.id', '=', 'x.driver_id')

            ->join('inquiries as i', 'i.id', '=', 'x.trip_id')

            ->join('customers as cr', 'cr.id', '=', 'i.customer_id')

            ->join('coaches as c', 'c.id', '=', 'x.coach_id')

            ->where('x.id', $id)

            ->select('x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.pick_up_time', 'i.destination', 'i.trip_start_date', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name')

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

            ->select('x.id as coach_driver_trip_id', 'x.reporting_time', 'x.departure_time', 'i.pick_up_point', 'i.id as inquiry_id', 'i.customer_id', 'i.pick_up_time', 'i.destination', 'i.trip_start_date', 'i.return_date', 'i.no_of_wheelchairs', 'i.driver_sheet_notes', 'c.registration_no', 'cr.name', 'cr.email', 'cr.phone', 'cr.address', 'u.name as driver_name', 'u.id as driver_id')

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



        $_SESSION['is_school'] = $request->is_school;

        $is_save = Excel::import(new TripImport, request()->file('file'));

        $data = ImportCsv::first();

        if($data){
            ImportCsv::where('id', $data->id)->delete();
            $message = "Success! ".$data->success." Trip imported successfully and ".$data->failed." Trip failed ";
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

            exit();
        }
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

            'total_charge'       => round($getdata->total_charge),

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

            'total_charge'       => round($getdata->total_charge),

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
