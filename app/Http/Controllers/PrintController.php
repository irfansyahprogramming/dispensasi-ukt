<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DataTables;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use stdClass;


class PrintController extends Controller
{
    // excel number formatting
    const FORMAT_ACCOUNTING_IDR = '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)';
    const FORMAT_DATE_DDMMYYYY = 'dd/mm/yyyy';
    
    public function __construct()
    {
        $this->text_bold = array(
            'font' => array(
                'bold' => true, 'color' => array('rgb' => '000000')
            )
        );

        $this->center = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $this->center_bold = array(
            'font' => array('bold' => true, 'color' => array('rgb' => '000000')),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $this->align_top = array(
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_TOP
            )
        );

        $this->border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];


        $this->border_outline = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $this->border_left = [
            'borders' => [
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $this->border_right = [
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $this->border_bottom = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
    }


}
