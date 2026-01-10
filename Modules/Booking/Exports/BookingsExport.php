<?php

namespace Modules\Booking\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Modules\Booking\Entities\BookingProperty;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BookingsExport implements WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize, WithEvents, FromCollection
{
	private $query, $type;

	public function __construct($type, $query)
	{
		$this->type = $type;
		$this->query = $query;
	}
//	public function query()
//	{
//		return $this->query;
//	}
	public function collection()
	{
		$data = $this->query->get();
		$dataBuild = [];
		foreach ($data as $index => $row) {
			$dataBuild[$index] = $row;
			$dataBuild[$index]->index = $index + 1;
		}
		return (collect($dataBuild));
	}
	public function map($item): array
	{
	    //Dữ liệu
	    $arr_value = [
            $item->index,
            $item->code,
            optional($item->creator)->name,
            optional($item->customer)->name,
            optional($item->customer)->phone,
            optional($item->bookingItem)->count('quantity'),
            number_format($item->total_price),
            optional($item->approve)->name,
            $item->note,
            Carbon::parse($item->created_at),
//            Date::dateTimeToExcel(Carbon::parse($item->updated_at))
        ];

        //Bổ sung các trường properties cho từng module
        $data_properties = [];
        foreach ($item->properties as $item){
//            $data_properties[] = ($key->type == 'date') ? Date::dateTimeToExcel(Carbon::parse(optional($key->pivot)->value)) : optional($key->pivot)->value;
            if ($item->type == 'date'){
                $data_properties[] = Date::dateTimeToExcel(Carbon::parse(optional($item->pivot)->value));
            }else if ($item->type == 'select'){
                $data_select = BookingProperty::getDataSelect(null, $this->type);
                foreach ($data_select as $key => $value){
                    if ($key == 'properties['.$item->key.']'){
                        $data_properties[] = $value[$item->pivot->value];
                    }
                }
            }else{
                $data_properties[] = $item->pivot->value;
            }
        }
        $arr_value = array_merge($arr_value, $data_properties);
        return $arr_value;
	}
	/**
	 * @return array
	 */
	public function columnFormats(): array
	{
		return [
			'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
			'I' => NumberFormat::FORMAT_GENERAL,
		];
	}
	public function headings(): array
	{
	    $arr_value = [
            trans('message.index'),
            trans('booking::bookings.code'),
            trans('booking::bookings.creator_id'),
            trans('booking::customers.name'),
            trans('booking::customers.phone'),
            trans('booking::bookings.total_product'),
            trans('booking::bookings.total_price'),
            trans('booking::bookings.approved'),
            trans('booking::bookings.note'),
            trans('message.created_at'),
        ];

	    //Bổ sung các trường properties cho từng module
        $module_properties = BookingProperty::where('module', $this->type)->pluck('key', 'id');
        $data_properties = [];
        foreach ($module_properties as $key => $value){
            $data_properties[] = trans($this->type.'::bookings.'.$value);
        }

        $arr_value = array_merge($arr_value, $data_properties);;
		return $arr_value;
	}
	/**
	 * @return array
	 */
	public function registerEvents(): array
	{
		return [
			AfterSheet::class    => function(AfterSheet $event) {
				$count = $this->query->count();
				$count++;//them header
				$allColumn = ['A','B','C','D','E','F','G','H','i', 'J'];
				//set font header
				$cellRange = 'A1:S1'; // All headers
				$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(true);

//				$event->sheet->getColumnDimension('J')->setWidth(12);
//				$event->sheet->getColumnDimension('L')->setWidth(12);
				$event->sheet->getStyle("J1:J$count")
				             ->getAlignment()->setWrapText(true);
				$event->sheet->getStyle("L1:L$count")
				            ->getAlignment()->setWrapText(true);
				$styleArray = [
						'borders' => [
							'allBorders' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							],
						]
					];
				foreach ($allColumn as $col){
					$event->sheet->getStyle($col."1:$col$count")->applyFromArray($styleArray);
				}

			},
		];
	}
}
