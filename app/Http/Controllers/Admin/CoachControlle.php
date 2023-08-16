<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use App\Models\Coach;
use App\Models\CoachType;
use Validator;

use DB;

class CoachControlle extends Controller
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
        $timestamp = strtotime(date("Y-m-d h:i:sa"));
        $timestamp += 6 * 3600;



        $results = DB::select( DB::raw("SELECT bookings.coach_id as coach_id FROM coaches INNER JOIN bookings on coaches.id = bookings.coach_id and bookings.booking_end >= ".$timestamp." and bookings.booking_start <= ".$timestamp) );
        
        $bookedCoachList = [];
        
        foreach ($results as $key => $value) {
            $bookedCoachList[] = $results[$key]->coach_id;    
        }

        //get all coaches

        $coaches=DB::table('coaches as c')
        ->join('coach_types as t', 'c.coach_type','=','t.id')
        ->select('c.id','c.registration_no','c.coach_name','c.status','t.type')
        ->get();
        //return view
        return view('admin.template.Coach.coach', compact('coaches', 'bookedCoachList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get coach types 
        $coach_types=CoachType::where('status','=','1')->get()->toArray();
        //dd($coach_types);
 
        return view('admin.template.Coach.create_coach',compact('coach_types'));
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
            'coach_name'      => 'required|unique:coaches,coach_name|max:255',
            'registration_no' => 'required|unique:coaches,registration_no|max:255',
            'coach_type'      => 'required',
            'status'          => 'required',
        ]);
        if($validation->fails()){
            //get all errors
            $error=$validation->errors();
            return response()->json(['error' => $error]);
        }
        if($validation->passes())
        {
            $data = array(
                   'coach_name'      => $request->get('coach_name'),
                   'registration_no' => $request->get('registration_no'),
                   'coach_type'     => $request->get('coach_type'),
                   'status'          => $request->get('status'),
                );
        
            $is_save=Coach::insert($data);
            if($is_save)
            {
                $success = array('msg' =>'Created Successfully.');
                return response()->json(['success' =>$success]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coach = Coach::find($id);
       //get coach types 
        $coach_types=CoachType::where('status','=','1')->get()->toArray();
       
        return view('admin.template.Coach.edit_coach', compact('coach','coach_types'));
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
        $validation = Validator::make($request->all(),[
         'coach_name'    => 'required|unique:coaches,coach_name,'.$id.'|max:255',
         'registration_no'  => 'required|unique:coaches,registration_no,'.$id.'|max:255',
         'coach_type'   => 'required',
         'status'        => 'required',
        ])->validate();
        
        $data = array(
                   'coach_name'      => $request->get('coach_name'),
                   'registration_no' => $request->get('registration_no'),
                   'coach_type'      => $request->get('coach_type'),
                   'status'          => $request->get('status'),
                );

        $is_update=Coach::where('id','=',$id)->update($data);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $is_delete=Coach::where('id',$id)->delete();
        if($is_delete)
        {
            session()->flash("success","Success! Coach has been deleted.");
            return redirect()->back();
        }else
        {
            session()->flash("info","Something went wrong!");
            return redirect()->back();
        }
    }
}
