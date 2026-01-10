<?php

namespace Modules\Booking\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BookingsImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            0 => new FirstSheetImport()
        ];
    }

}

class FirstSheetImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        try {
            if ($rows != null && count($rows) > 0) {
                return $rows;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}