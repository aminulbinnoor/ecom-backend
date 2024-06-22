<?php

namespace App\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithMapping;



class Export implements FromArray, WithHeadings,ShouldAutoSize,WithStyles
{

    protected $is_active;
    protected $headers = [];
    protected $datas = [];

    public function __construct($headers,$datas)
    {
      $this->headers = $headers;
      $this->datas = $datas;
    }

    public function array(): array
    {
        return $this->datas;

    }



    // public function startCell(): string
    // {
    //     return 'B2';
    // }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            // 'A'    => ['font' => ['bold' => true]],
            //
            // // Styling a specific cell by coordinate.
            // 'B' => ['font' => ['italic' => true]],
            //
            // // Styling an entire column.
            // 'C'  => ['font' => ['bold' => true,'size' => 16]],
        ];
    }

    public function headings(): array
    {
        return $this->headers;
    }
}
