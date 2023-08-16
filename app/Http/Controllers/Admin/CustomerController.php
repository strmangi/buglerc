<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Imports\CustomerImport;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::where('status',1)->latest()->get();
        return view('admin.template.Customer.customer', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.template.Customer.create_customer');
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
            'customer_name'  => 'required|string|max:255',
            'email'          => 'required|unique:customers,email|max:255',
            'phone'          => 'required|min:8',
            'address'        => 'required|string',
        ])->validate();
      
            $data = array(
                'name'          => $request->get('customer_name'),
                'email'         => $request->get('email'),
                'phone'         => $request->get('phone'),
                'address'       => $request->get('address'),
                'status'        => 1,
                );
        
            $is_save=Customer::insert($data);
            if($is_save)
            {
                session()->flash("success","Customer has been created successfully");
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
        $customer = Customer::find($id);
        return view('admin.template.Customer.edit_customer', compact('customer'));
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
            'customer_name'  => 'required|string|max:255',
            'email'          => 'required|unique:customers,email,'.$id,
            'phone'          => 'required|min:8',
            'address'        => 'required|string',
        ])->validate();
      
            $data = array(
                'name'          => $request->get('customer_name'),
                'email'         => $request->get('email'),
                'phone'         => $request->get('phone'),
                'address'       => $request->get('address'),
                'status'        => 1,
                );
        
            $customer =Customer::find($id);
            $is_update = $customer->update($data);

            if($is_update)
            {
                session()->flash("success","Customer has been updated successfully");
                return redirect()->route('admin.customer.index');
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
       

        $user = Customer::find($id); 
        $user->delete(); //delete the client
        $is_delete = DB::table('customers')->where('id',$id)->delete(); 
        if($is_delete)
        {
            session()->flash("success","Customer has been deleted successfully");
            return redirect()->route('admin.customer.index');
        }
        else
        {
            session()->flash("error","Something went wrong!");
            return redirect()->back();    
        }

    }

    public function import() 
    {
        $is_save = Excel::import(new CustomerImport,request()->file('file'));
        if($is_save)
        {
            session()->flash("success","Success! Customer imported successfully");
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
