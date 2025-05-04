<?php

namespace App\Exports;

use App\Models\Donation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class DonationsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Donation::with(['campaign', 'donor']);

        // Apply date filter
        if ($this->request->filled('date_range')) {
            $days = $this->request->input('date_range');
            $startDate = Carbon::now()->subDays($days);
            $query->where('created_at', '>=', $startDate);
        }

        // Apply campaign filter
        if ($this->request->filled('campaign_id')) {
            $query->where('campaign_id', $this->request->input('campaign_id'));
        }

        // Apply status filter
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->input('status'));
        }

        return $query->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Donor Name',
            'Campaign',
            'Type',
            'Status',
            'Date',
            'Description',
            'Drop-off Location',
            'Drop-off Date'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->donor->name,
            $row->campaign->title,
            ucfirst($row->type),
            ucfirst($row->status),
            $row->created_at->format('Y-m-d H:i:s'),
            $row->description,
            $row->dropoff_location ?? 'N/A',
            $row->dropoff_date ? $row->dropoff_date->format('Y-m-d') : 'N/A'
        ];
    }
} 