<?php

namespace App\Imports;

use App\Models\Forecast;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsTransactions;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ForecastImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $fecha = $row['fecha'];
        if(is_numeric($fecha)){

            // Si la fecha viene como número de Excel, convertir
           $fecha = ExcelDate::excelToDateTimeObject($fecha)->format('Y-m-d');
        } else {
            // Si ya viene como string, normalizar
            $fecha = Carbon::parse($fecha)->format('Y-m-d');
        }
        return new Forecast([
            'variable_id' => $row['variable_id'],
            'fecha' => $fecha,
            'valor' => $row['valor'],
            'estado' => $row['estado'],
        ]);
    }
}
