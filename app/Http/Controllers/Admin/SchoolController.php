<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Imports\SchoolImport;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = School::latest()->get();
        return view('admin.template.School.school', compact('school'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.template.School.create_school');
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
            'Name'          => 'required|unique:school,Name|max:255',
            'Address'        => 'required|string',
        ])->validate();
      
            $data = array(
                'name'          => $request->get('Name'),
                'Address'       => $request->get('Address'),
               
                );
        
            $is_save=School::insert($data);
            if($is_save)
            {
                session()->flash("success","School has been created successfully");
                return redirect()->back();
            }
            else
            {
                session()->flash("error","Something went wrong!");
                return redirect()->back();    
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
        $school = School::where('SchoolID', $id)->first();
        // $school = School::find($id);
        return view('admin.template.School.edit_school', compact('school'));
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
            'Name'          => 'required',
            'Address'        => 'required|string',
        ])->validate();
      
            $data = array(
                'Name'          => $request->get('Name'),
                'Address'       => $request->get('Address'),
               
                );
        
            $school =School::where('SchoolID', $id)->first();
            $school->Name =  $data['Name'];
            $school->Address =  $data['Address'];
            $is_update =  $school->save();

            if($is_update)
            {
                session()->flash("success","School has been updated successfully");
                return redirect()->route('admin.school.index');
            }
            else
            {
                session()->flash("error","Something went wrong!");
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
        $is_delete = School::where('SchoolID', $id)->delete();
        if($is_delete)
        {
            session()->flash("success","School has been deleted successfully");
            return redirect()->route('admin.school.index');
        }
        else
        {
            session()->flash("error","Something went wrong!");
            return redirect()->back();    
        }

    }

    public function import() 
    {
        $is_save = Excel::import(new SchoolImport,request()->file('file'));
        if($is_save)
        {
            session()->flash("success","Success! School imported successfully");
            return redirect()->back();
        }else
        {
            session()->flash("info","Something went wrong!");
            return redirect()->back();
        }
        
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new CustomerExport, 'customers.xlsx');
    }
   
}
