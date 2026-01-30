<?php

namespace App\Exports;

use App\Models\Mobile;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MobilesExport implements FromCollection, WithHeadings
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
        return Mobile::select('mobile_name', 'imei_number', 'color', 'sim_lock', 'storage', 'battery_health', 'cost_price', 'selling_price', 'created_at')
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            ->where('original_owner_id', Auth::id())
            ->where('availability', 'Available')
            ->where('is_transfer', false)
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
            'Cost Price',
            'Selling Price',
            'Created At',
        ];
    }
}
