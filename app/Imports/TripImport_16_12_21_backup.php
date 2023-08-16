<?php

namespace App\Imports;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\School;
use App\Models\Inquiry;
use App\Models\CoachType;
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

         //dd($rows);
        foreach ($rows as $row) 
        {      

            // echo "New Date : ".$newDate."<br>";
            // echo "<pre>"; print_r($row); die("check");
    
            $destination = School::where('Name', $row['customer_name'])->first();
            
            if(!empty($destination->Address)){

                $d_dest = $destination->Address;
                
            }else{
                $d_dest = '';

            }

            $is_school = 0;
            if(empty($row['destination'])){
                $row['destination'] = $d_dest;
                $is_school = 1;
               
            }


            


            if(isset($row['customer_email']) && !empty($row['customer_email'])){


                $checkCustomer = Customer::where('email',$row['customer_email'])->first();
                if($checkCustomer)
                {
                    $customerId = $checkCustomer->id;
                }
                else
                {   

                    $is_save = Customer::insertGetId([
                        'name'    => $row['customer_name'],
                        'email'   => $row['customer_email'],
                        'phone'   => $row['phone'],
                        'address' => $row['address'],
                    ]);
                    
                    $customerId =  $is_save;
                } 
                

                if(!empty($row['school_start_date']) &&  !empty($row['school_close_date'])){
                    $start_date = date("Y-m-d", strtotime($row['school_start_date']));
                    $end_date = date("Y-m-d", strtotime($row['school_close_date']));


                    while ($start_date <= $end_date) {
        
                        $inquiry= Inquiry::insertGetId(
                        [
                            'customer_id'          => $customerId,
                            'pick_up_point'        => $row['pick_up_point'],
                            'pick_up_time'         => $row['pick_up_time'],
                            'destination'          => $row['destination'],
                            'trip_start_date'      => $start_date,
                            'trip_starting_time'   => strtotime($start_date.' '.$row['pick_up_time']), 

                            'school_start_date'    => (isset($row['school_start_date'])) ? $row['school_start_date'] : null ,
                            'school_close_date'    => (isset($row['school_close_date'])) ? $row['school_close_date'] : null ,
                            'am_pm'                => (isset($row['am_pm'])) ? $row['am_pm'] : null ,
                            'return_date'          => $row['return_date'],
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
                            'quotation_no'         => 123456,
                            'no_of_passengers'     => $row['no_of_passengers'],
                            'no_of_wheelchairs'    => $row['no_of_wheelchairs'],
                            'booking_date'         => $row['booking_date'],
                            'driver_sheet_notes'   => $row['driver_sheet_notes'],
                            'report_time'          => (isset($row['report_time'])) ? $row['report_time'] : null ,
                            'is_school'            => $is_school,
                            'status'               => 'Confirmed',
                            
                        ]);

                        $start_date = date("Y-m-d", strtotime($start_date . ' +1 day'));

                        $checkCoachType = CoachType::where('type',$row['coach_type'])->first();
                        if($checkCoachType)
                        {
                            $coachtype_id = $checkCoachType->id;
                        }
                        else 
                        {
                            $coachtype_id = CoachType::insertGetId([
                                'type'            => $row['coach_type'],
                                'cost_per_mile'   => $row['cost_per_mile'],
                                'cost_per_day'    => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                                'cost_per_driver' => $row['driver_hire_cost_per_hour'],
                            ]);
                        }

                        $is_save =CoachTypesInquiries::insertGetId([
                            'coach_type_id'  => $coachtype_id,
                            'inquiry_id'     => $inquiry,
                            'no_of_coach'    => (isset($row['no_of_coach'])) ? $row['no_of_coach'] : 0 ,
                            'cost_per_mile'  => $row['cost_per_mile'],
                            'no_of_days'     => (isset($row['no_of_days'])) ? $row['no_of_days'] : 0 ,
                            'cost_per_day'   => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                            'driver_per_hour_cost' =>  (isset($row['driver_per_hour_cost'])) ? $row['driver_per_hour_cost'] : 0,
                            'total_charge'   => (isset($row['total_charges_for_this_coach'])) ? $row['total_charges_for_this_coach'] : 0,
                        ]);

                        
                    }
                     

                }else{
                        

                    $inquiry= Inquiry::insertGetId(
                        [
                            'customer_id'          => $customerId,
                            'pick_up_point'        => $row['pick_up_point'],
                            'pick_up_time'         => $row['pick_up_time'],
                            'destination'          => $row['destination'],
                            'trip_start_date'      => (isset($row['trip_start_date'])) ? date("Y-m-d", strtotime($row['trip_start_date'])) : null ,
                            'trip_starting_time'   => strtotime($row['trip_start_date'].' '.$row['pick_up_time']), 
                            'school_start_date'    => (isset($row['school_start_date'])) ? $row['school_start_date'] : null ,
                            'school_close_date'    => (isset($row['school_close_date'])) ? $row['school_close_date'] : null ,
                            'am_pm'                => (isset($row['am_pm'])) ? $row['am_pm'] : null ,
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
                            'quotation_no'         => 123456,
                            'no_of_passengers'     => $row['no_of_passengers'],
                            'no_of_wheelchairs'    => $row['no_of_wheelchairs'],
                            'booking_date'         => date("Y-m-d", strtotime($row['booking_date'])),
                            'driver_sheet_notes'   => $row['driver_sheet_notes'],
                            'is_school'            => $_SESSION['is_school'],
                            'report_time'          => (isset($row['report_time'])) ? $row['report_time'] : null ,
                            'status'               => 'Confirmed',
                            
                        ]);


                    $checkCoachType = CoachType::where('type',$row['coach_type'])->first();
                    if($checkCoachType)
                    {
                        $coachtype_id = $checkCoachType->id;
                    }
                    else 
                    {
                        $coachtype_id = CoachType::insertGetId([
                            'type'            => $row['coach_type'],
                            'cost_per_mile'   => $row['cost_per_mile'],
                            'cost_per_day'    => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                            'cost_per_driver' => $row['driver_hire_cost_per_hour'],
                        ]);
                    }

                    $is_save =CoachTypesInquiries::insertGetId([
                        'coach_type_id'  => $coachtype_id,
                        'inquiry_id'     => $inquiry,
                        'no_of_coach'    => (isset($row['no_of_coach'])) ? $row['no_of_coach'] : 0 ,
                        'cost_per_mile'  => $row['cost_per_mile'],
                        'no_of_days'     => (isset($row['no_of_days'])) ? $row['no_of_days'] : 0 ,
                        'cost_per_day'   => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                        'driver_per_hour_cost' =>  (isset($row['driver_per_hour_cost'])) ? $row['driver_per_hour_cost'] : 0,
                        'total_charge'   => (isset($row['total_charges_for_this_coach'])) ? $row['total_charges_for_this_coach'] : 0,
                    ]);


                }






               
            }else{



                $checkCustomer = Customer::where('name',$row['customer_name'])->first();
                if($checkCustomer)
                {
                    $customerId = $checkCustomer->id;
                }
                else
                {
                    $is_save = Customer::insertGetId([
                        'name'    => $row['customer_name'],
                        'email'   =>  str_replace(' ', '_', $row['customer_name']).'@gmail.com',
                    ]);
                    
                    $customerId =  $is_save;
                } 



            
                if(!empty($row['school_start_date']) &&  !empty($row['school_close_date'])){
                    
                    $c_date = str_replace("/",'-',$row['school_start_date']);
                    $start_date = date("Y-m-d", strtotime($c_date));
                    //$end_date = date("Y-m-d", strtotime(trim($row['school_close_date'],'')));
                    
                    $c_date = str_replace("/",'-',$row['school_close_date']);
                      $end_date = date("Y-m-d", strtotime($c_date));
                      
                      

                   
                    while ($start_date <= $end_date) {


                        
                         $inquiry= Inquiry::insertGetId(
                        [
                            'customer_id'          => $customerId,
                            'pick_up_point'        => $row['pick_up_point'],
                            'pick_up_time'         => $row['pick_up_time'],
                            'no_of_passengers'     => $row['no_of_passengers'],
                            'trip_start_date'      => $start_date,
                            'school_start_date'    => (isset($row['school_start_date'])) ? $row['school_start_date'] : null ,
                            'school_close_date'    => (isset($row['school_close_date'])) ? $row['school_close_date'] : null ,
                            'am_pm'                => (isset($row['am_pm'])) ? $row['am_pm'] : null ,
                            'quated_by'            => 1,
                            'quotation_no'         => 123456,
                            'is_school'            => $is_school,
                            'report_time'          => (isset($row['report_time'])) ? $row['report_time'] : null ,
                            'status'               => 'Confirmed',
                            
                        ]);
                        $start_date = date("Y-m-d", strtotime($start_date . ' +1 day'));

                        $checkCoachType = CoachType::where('type',$row['coach_type'])->first();
                        if($checkCoachType)
                        {
                            $coachtype_id = $checkCoachType->id;
                        }
                        else 
                        {
                            $coachtype_id = CoachType::insertGetId([
                                'type'            => $row['coach_type'],
                                'cost_per_mile'   => (isset($row['cost_per_mile'])) ? $row['cost_per_mile'] : 0 ,
                                'cost_per_day'    => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                                'cost_per_driver' => $row['driver_hire_cost_per_hour'],
                            ]);
                        }


                        $is_save =CoachTypesInquiries::insertGetId([
                            'coach_type_id'  => $coachtype_id,
                            'inquiry_id'     => $inquiry,
                            'no_of_coach'    => (isset($row['no_of_coach'])) ? $row['no_of_coach'] : 0 ,
                            'cost_per_mile'  => (isset($row['cost_per_mile'])) ? $row['cost_per_mile'] : 0 ,
                            'no_of_days'     => (isset($row['no_of_days'])) ? $row['no_of_days'] : 0 ,
                            'cost_per_day'   => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                            'driver_per_hour_cost' =>  (isset($row['driver_per_hour_cost'])) ? $row['driver_per_hour_cost'] : 0,
                            'total_charge'   => (isset($row['total_charges_for_this_coach'])) ? $row['total_charges_for_this_coach'] : 0,
                        ]);

//print_r('inwhile');
                    }
                   // print_r('while out');
                     
                }else{
                     

                    $inquiry= Inquiry::insertGetId(
                    [
                        'customer_id'          => $customerId,
                        'pick_up_point'        => $row['pick_up_point'],
                        'pick_up_time'         => $row['pick_up_time'],
                        'no_of_passengers'     => $row['no_of_passengers'],
                        'trip_start_date'      => (isset($row['school_start_date'])) ? $row['school_start_date'] : null ,
                        'school_start_date'    => (isset($row['school_start_date'])) ? $row['school_start_date'] : null ,
                        'school_close_date'    => (isset($row['school_close_date'])) ? $row['school_close_date'] : null ,
                        'am_pm'                => (isset($row['am_pm'])) ? $row['am_pm'] : null ,
                        'quated_by'            => 1,
                        'quotation_no'         => 123456,
                        'is_school'            => $is_school,
                        'report_time'          => (isset($row['report_time'])) ? $row['report_time'] : null ,
                        'status'               => 'Confirmed',
                        
                    ]);

                     $checkCoachType = CoachType::where('type',$row['coach_type'])->first();
                    if($checkCoachType)
                    {
                        $coachtype_id = $checkCoachType->id;
                    }
                    else 
                    {
                        $coachtype_id = CoachType::insertGetId([
                            'type'            => $row['coach_type'],
                            'cost_per_mile'   => (isset($row['cost_per_mile'])) ? $row['cost_per_mile'] : 0 ,
                            'cost_per_day'    => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                            'cost_per_driver' => $row['driver_hire_cost_per_hour'],
                        ]);
                    }


                    $is_save =CoachTypesInquiries::insertGetId([
                        'coach_type_id'  => $coachtype_id,
                        'inquiry_id'     => $inquiry,
                        'no_of_coach'    => (isset($row['no_of_coach'])) ? $row['no_of_coach'] : 0 ,
                        'cost_per_mile'  => (isset($row['cost_per_mile'])) ? $row['cost_per_mile'] : 0 ,
                        'no_of_days'     => (isset($row['no_of_days'])) ? $row['no_of_days'] : 0 ,
                        'cost_per_day'   => (isset($row['cost_per_day'])) ? $row['cost_per_day'] : 0 ,
                        'driver_per_hour_cost' =>  (isset($row['driver_per_hour_cost'])) ? $row['driver_per_hour_cost'] : 0,
                        'total_charge'   => (isset($row['total_charges_for_this_coach'])) ? $row['total_charges_for_this_coach'] : 0,
                    ]);




                }


               


               
            }
                
                  
            
           
        }
      
    }
}
