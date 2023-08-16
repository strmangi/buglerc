<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoachType;
use Gate;
use DB;
use Validator;
class CoachTypeController extends Controller
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
        //dd('fg dfgf');

        $types = CoachType::all();
       
        return view('admin.template.CoachType.type', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.template.CoachType.create_coachtype');
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
            'coach_type'      => 'required|unique:coach_types,type|max:255',
            'cost_per_mile'   => 'required',
            'cost_per_day'    => 'required',
            'cost_per_driver' => 'required',
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
                   'type'           => $request->get('coach_type'),
                   'cost_per_mile'  => $request->get('cost_per_mile'),
                   'cost_per_day'   => $request->get('cost_per_day'),
                   'cost_per_driver'   => $request->get('cost_per_driver'),
                   'status'         => $request->get('status'),
                );
        
            $is_save=CoachType::insert($data);
            if($is_save)
            {
                $success = array('msg' =>'Coach Type Created Successfully.');
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
        $coach_types = CoachType::find($id);
       //get coach types 
      
        return view('admin.template.CoachType.edit_type', compact('coach_types'));
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
            'coach_type'      => 'required|unique:coach_types,type,'.$id.'|max:255',
            'cost_per_mile'   => 'required',
            'cost_per_day'    => 'required',
            'cost_per_driver' => 'required',
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
                   'type'           => $request->get('coach_type'),
                   'cost_per_mile'  => $request->get('cost_per_mile'),
                   'cost_per_day'   => $request->get('cost_per_day'),
                   'cost_per_driver'   => $request->get('cost_per_driver'),
                   'status'         => $request->get('status'),
                );
            $is_update=CoachType::where('id','=',$id)->update($data);

            if($is_update){
               session()->flash("success","Update Successfully!");
                return redirect()->back();
            }else{
                session()->flash('info', 'Update failed!');
                return redirect()->back();
            }
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
        $is_delete=CoachType::where('id',$id)->delete();
        if($is_delete)
        {
            session()->flash("success","Success! Coach-Type has been deleted.");
            return redirect()->back();
        }else
        {
            session()->flash("info","Something went wrong!");
            return redirect()->back();
        }
    }
}
