<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
 
        return new Customer([
            'name'    => $row['name'],
            'email'   => $row['email'],
            'phone'   => $row['phone'],
            'address' => $row['address'],
            'status'  => '1',
        ]);
    }
}
