<?php

namespace App\Exports;

use App\Models\Mobile;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SoldMobilesExport implements FromCollection, WithHeadings
{
    protected $mobiles;
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return Mobile::select('mobile_name', 'imei_number', 'color', 'sim_lock', 'storage', 'battery_health','customer_name','is_approve', 'cost_price', 'selling_price', 'sold_at')
            ->whereBetween('created_at', [$this->start_date, $this->end_date])->
            where('user_id', auth()->user()->id)->where('availability', 'Sold')->where('is_transfer', false)
            ->get();

    }


    public function headings(): array
    {
        return [

            'Mobile Name',
            'IMEI Number',
            'Color',
            'SIM Lock',
            'Storage',
            'Battery Health',
            'Customer Name',
            'Approved Status',
            'Cost Price',
            'Selling Price',
            'Sold At',
        ];
    }
}
