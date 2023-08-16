<?php
namespace App\Imports;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\School;
use App\Models\Inquiry;
use App\Models\CoachType;
use App\Models\ImportCsv;
use App\Models\CoachTypesInquiries;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TripImport implements ToCollection, WithHeadingRow

{

    /**
     * @param Collection $collection
     */

    public function collection(Collection $rows)
    {
        $total_failed = count($rows);
        $total_success = array();
        // dd($total_failed);

       
        foreach ($rows as $row) {

            $quotation_no = $this->generateQuatationNo();
            $destination = Customer::where('Name', $row['customer_name'])->first();

            if (!empty($destination->Address)) {
                $d_dest = $destination->Address;
            } else {
                $d_dest = '';
            }

            $is_school = 0;
            if (empty($row['destination'])) {
                $row['destination'] = $d_dest;
                $is_school = 1;
            }


            if (isset($row['customer_email']) && !empty($row['customer_email'])) {

                $checkCustomer = Customer::where('email', $row['customer_email'])->first();
                if ($checkCustomer) {

                  $customerId = $checkCustomer->id;

                  
                } else {

                    $is_save = Customer::insertGetId([
                        'name'    => $row['customer_name'],
                        'email'   => $row['customer_email'],
                        'phone'   => $row['phone'],
                        'address' => $row['address'],
                        'status'  => '1',
                    ]);

                    $customerId =  $is_save;
                  
                }

                if (!empty($row['trip_start_date']) &&  !empty($row['return_date'])) {
                    $total_failed = $total_failed - 1;

                 

                    if (str_contains($row['trip_start_date'], '/')) {
                        $s_date = str_replace("/", '-', $row['trip_start_date']);
                        $c_date = str_replace("/", '-', $row['return_date']);
                    }else{
                        $s_date =  $row['trip_start_date'];
                        $c_date =  $row['return_date'];
                    }
                   
                     /* $start_date = date("Y-m-d", strtotime($s_date));
                     $end_date = date("Y-m-d", strtotime($c_date)); */

                     $start_date = date('Y-m-d',strtotime($row['trip_start_date']));
                     $end_date = date("Y-m-d", strtotime($row['return_date']));
                  
                  

                    $check_date_format = is_numeric( strtotime($start_date) ) ? true : false;


                    if($check_date_format && $start_date >= date("Y-m-d")){

                    if($check_date_format){
                            $dates = array();
                          $current = strtotime($start_date);
                          
                            $date2 = strtotime($end_date);
                            
                            $stepVal = '+1 day';
                            while( $current <= $date2 ) {
                              
                            $dates[] = date("Y-m-d", $current);
                            $current = strtotime($stepVal, $current);
                            }

                            $arr = [];
                            foreach($dates as $ks => $vs){

                              

                                $check_date = in_array(date("l", strtotime($vs)), ["Saturday", "Sunday"]);
                                if(!$check_date){
                                    $arr[] = $vs;
                                }
                            }

                            $start_date = $arr[0];
                            $date_count = count($arr)-1;
                            $end_date = $arr[$date_count];



                        // Using If Condition to check start date and end date
                        // Commenting while due creating duplecacy of record.

                        // while ($start_date <= $end_date) {
                        if ($start_date <= $end_date) {
                            foreach($arr as $updated_date){

                               
                                $inquiry = Inquiry::insertGetId(
                                    [
                                        'customer_id'          => $customerId,
                                        'pick_up_point'        => $row['pick_up_point'],
                                        'pick_up_time'         => $row['pick_up_time'],
                                        'destination'          => $row['destination'],
                                        'trip_start_date'      => $updated_date,
                                        'trip_starting_time'   => strtotime($updated_date . ' ' . $row['pick_up_time']),
                                        'school_start_date'    => (isset($updated_date)) ? $updated_date : null,
                                        'school_close_date'    => (isset($updated_date)) ? $updated_date : null,
                                        'am_pm'                => (isset($row['am_pm'])) ? $row['am_pm'] : null,
                                        'return_date'          => date("Y-m-d", strtotime($row['return_date'])),
                                        'total_mileage'        => $row['total_mileage'],
                                        'no_of_driver_hours'   => $row['no_of_driver_hours'],
                                        'supplemental_costs_1' => $row['supplemental_costs_1'],
                                        'supplemental_costs_2' => $row['supplemental_costs_2'],
                                        'supplemental_costs_3' => $row['supplemental_costs_3'],
                                        'deposit_required'     => $row['deposit_required'],
                                        'balance_outstanding'  => $row['balance_outstanding'],
                                        'total_charge'         => $row['total_charge'],
                                        'deposit_received'     => $row['deposit_received'],
                                        'quated_by'            => 1,
                                        'quotation_no'         => $quotation_no,
                                        'no_of_passengers'     => $row['no_of_passengers'],
                                        'no_of_wheelchairs'    => $row['no_of_wheelchairs'],
                                        'booking_date'         => date("Y-m-d", strtotime($row['booking_date'])),
                                        'driver_sheet_notes'   => $row['driver_sheet_notes'],
                                        'report_time'          => (isset($row['report_time'])) ? $row['report_time'] : null,
                                        'is_school'            => $is_school,
                                        'status'               => 'Confirmed',
                                    ]
                                );


                                $start_date = date("Y-m-d", strtotime($start_date . ' +1 day'));

                                $checkCoachType = CoachType::where('type', $row['coach_type'])->first();

                                if ($checkCoachType) {
                                    $coachtype_id = $checkCoachType->id;
                                } else {
                                    $coachtype_id = CoachType::insertGetId([
                                        'type'            => $row['coach_type'],
                                        'cost_per_mile'   => $row['cost_per_mile'],
                                        'cost_per_day'    => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0,

                                        'cost_per_driver'         => (isset($row['driver_hire_cost_per_hour'])) ? $row['driver_hire_cost_per_hour'] : 0,

                                    ]);
                                }


                                $is_save = CoachTypesInquiries::insertGetId([
                                    'coach_type_id'  => $coachtype_id,
                                    'inquiry_id'     => $inquiry,
                                    'no_of_coach'    => (isset($row['no_of_coach'])) ? $row['no_of_coach'] : 0,
                                    'cost_per_mile'  => $row['cost_per_mile'],
                                    'no_of_days'     => (isset($row['no_of_days'])) ? $row['no_of_days'] : 0,
                                    'cost_per_day'   => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0,
                                    'driver_per_hour_cost' => (isset($row['driver_per_hour_cost'])) ? $row['driver_per_hour_cost'] : 0,
                                    'total_charge'   => (isset($row['total_charges_for_this_coach'])) ? $row['total_charges_for_this_coach'] : 0,
                                ]);

                            }

                        }
                    }
                }
              }

            } else {

                $checkCustomer = Customer::where('name', $row['customer_name'])->first();
                if ($checkCustomer) {
                    $customerId = $checkCustomer->id;
                } else {
                    $is_save = Customer::insertGetId([
                        'name'    => $row['customer_name'],
                        'email'   =>  str_replace(' ', '_', $row['customer_name']) . '@gmail.com',
                    ]);
                    $customerId =  $is_save;
                }

                if (!empty($row['school_start_date']) &&  !empty($row['school_close_date'])) {

                    if (str_contains($row['school_start_date'], '/')) {
                        $s_date = str_replace("/", '-', $row['school_start_date']);
                        $c_date = str_replace("/", '-', $row['school_close_date']);
                    }else{
                        $s_date =  $row['school_start_date'];
                        $c_date =  $row['school_close_date'];
                    }
                    $start_date = date("Y-m-d", strtotime($s_date));
                    $end_date = date("Y-m-d", strtotime($c_date));

                    // dd($start_date);
                    $check_date_format = is_numeric( strtotime($start_date) ) ? true : false;

                    if($check_date_format && $start_date >= date("Y-m-d")){

                        $total_success[] = 1;

                        $dates = array();
                        $current = strtotime($start_date);
                        $date2 = strtotime($end_date);
                        $stepVal = '+1 day';

                        while( $current <= $date2 ) {
                        $dates[] = date("Y-m-d", $current);
                        $current = strtotime($stepVal, $current);
                        }

                        $arr = [];
                        foreach($dates as $ks => $vs){

                            $check_date = in_array(date("l", strtotime($vs)), ["Saturday", "Sunday"]);
                            if(!$check_date){
                                $arr[] = $vs;
                            }
                        }
                        // dd($current <= $date2);
                        $start_date = $arr[0];
                        $date_count = count($arr)-1;
                        $end_date = $arr[$date_count];
                      

                        // while ($start_date <= $end_date) {
                        if ($start_date <= $end_date) {
                            foreach($arr as $updated_date){
                                // dd($updated_date);
                                $inquiry = Inquiry::insertGetId(
                                    [
                                        'customer_id'          => $customerId,
                                        'pick_up_point'        => $row['pick_up_point'],
                                        'pick_up_time'         => $row['pick_up_time'],
                                        'no_of_passengers'     => $row['no_of_passengers'],
                                        'trip_start_date'      => $updated_date,
                                        'school_start_date'    => (isset($updated_date)) ? $updated_date : null,
                                        'school_close_date'    => (isset($updated_date)) ? $updated_date : null,
                                        'am_pm'                => (isset($row['am_pm'])) ? $row['am_pm'] : null,
                                        'quated_by'            => 1,
                                        'quotation_no'         => $quotation_no,
                                        'is_school'            => $is_school,
                                        'report_time'          => (isset($row['report_time'])) ? $row['report_time'] : null,
                                        'job_end_time'         => (isset($row['job_end_time'])) ? $row['job_end_time'] : null,
                                        'status'               => 'Confirmed',
                                    ]
                                );

                                $start_date = date("Y-m-d", strtotime($start_date . ' +1 day'));
                                $checkCoachType = CoachType::where('type', $row['coach_type'])->first();

                                if ($checkCoachType) {
                                    $coachtype_id = $checkCoachType->id;
                                } else {

                                    $coachtype_id = CoachType::insertGetId([
                                        'type'            => $row['coach_type'],
                                        'cost_per_mile'   => (isset($row['cost_per_mile'])) ? $row['cost_per_mile'] : 0, 
                                        'cost_per_day'    => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0,
                                        'cost_per_driver'         => (isset($row['driver_hire_cost_per_hour'])) ? $row['driver_hire_cost_per_hour'] : 0,

                                    ]);
                                }



                                $is_save = CoachTypesInquiries::insertGetId([
                                    'coach_type_id'  => $coachtype_id,
                                    'inquiry_id'     => $inquiry,
                                    'no_of_coach'    => (isset($row['no_of_coach'])) ? $row['no_of_coach'] : 0,
                                    'cost_per_mile'  => (isset($row['cost_per_mile'])) ? $row['cost_per_mile'] : 0,
                                    'no_of_days'     => (isset($row['no_of_days'])) ? $row['no_of_days'] : 0,
                                    'cost_per_day'   => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0,
                                    'driver_per_hour_cost' => (isset($row['driver_per_hour_cost'])) ? $row['driver_per_hour_cost'] : 0,
                                    'total_charge'   => (isset($row['total_charges_for_this_coach'])) ? $row['total_charges_for_this_coach'] : 0,
                                ]);

                                 
                            }
                        }
                    }

                }

            }

        }
        $total_failed = count($rows) - count($total_success);

        ImportCsv::create(['success'=>count($total_success), 'failed'=>$total_failed]);
    }

    //Generate Random and unique quotation_no
    public function generateQuatationNo() {
        do {
            $rndno = rand(111111, 999999);
            $quotation_no = $rndno;
        } while (!empty(Inquiry::where('quotation_no', $quotation_no)->first()));
        return $quotation_no;
    }
}
