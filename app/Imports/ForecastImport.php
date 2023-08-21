<?php

namespace App\Imports;

use App\Models\Forecast;
use Maatwebsite\Excel\Concerns\ToModel;

class ForecastImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Forecast([
            //
        ]);
    }
}
