<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Exception;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PDF;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function cetakPenerimaDispensasi($semester, $kode_prodi, Request $request)
    {
        // jika belum pilih format cetak
        if ($request->format == null or $request->format == '') {
            return redirect()->back()->with('toast_warning', 'Silahkan pilih format cetak laporan');
        }
        if (trim(session('user_cmode')) != '4' && trim(session('user_cmode')) != '11' && trim(session('user_cmode')) != '13'&& trim(session('user_cmode')) != '20'){
            $data_pengajuan = DB::table('tb_pengajuan_dispensasi')
            ->where('kode_prodi', 'like', $kode_prodi . '%')
            ->where('semester', trim($semester))
            ->where('status_pengajuan', '>=', '3')
            ->where('status_pengajuan', '<=', '7')
            ->get();
        }else{
            $data_pengajuan = DB::table('tb_pengajuan_dispensasi')
            ->where('semester', trim($semester))
            ->where('status_pengajuan', '>=', '3')
            ->where('status_pengajuan', '<=', '7')
            ->get();
        }
        

        foreach ($data_pengajuan as $ajuan) {
            $ajuan->nom_ukt = number_format($ajuan->nominal_ukt, 0);
            $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
            $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
            $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        }


        // jika pillih pdf
        if ($request->format == 'pdf') {
            return $this->_pdfPenerimaDispensasi($data_pengajuan, $semester, $kode_prodi);
        }

        // jika pilih excel
        if ($request->format == 'excel') {
            return $this->_excelPenerimaDispensasi($data_pengajuan, $semester, $kode_prodi);
        }

        // selain itu kembali ke halaman sebelumnya dengan toast msg
        return redirect()->back()->with('toast_error', 'Jenis cetak tidak ditemukan');
    }

    // cetak penerima dispensasi dalam bentuk excel
    private function _excelPenerimaDispensasi($data_pengajuan, $semester, $kode_prodi)
    {
        // excel inisiasi
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setShowGridlines(false);

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        // create table header
        $row = 1;
        $sheet->setCellValue('A' . $row, 'Daftar Penerima Dispensasi Unit ' . $kode_prodi . ' Semester' . $semester);
        $sheet->getStyle('A' . $row)->applyFromArray($this->center_bold);
        $sheet->mergeCells('A' . $row . ':I' . $row);

        $row = 3;
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($this->border);
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($this->text_bold);

        $sheet->setCellValue('A' . $row, 'No.');
        $sheet->setCellValue('B' . $row, 'NIM');
        $sheet->setCellValue('C' . $row, 'Nama');
        $sheet->setCellValue('D' . $row, 'Program Studi');
        $sheet->setCellValue('E' . $row, 'Kel. UKT');
        $sheet->setCellValue('F' . $row, 'Nominal UKT');
        $sheet->setCellValue('G' . $row, 'Jenis Dispensasi');
        // $sheet->setCellValue('H' . $row, 'File Pendukung');
        // $sheet->setCellValue('I' . $row, 'Status Pengajuan Dispensasi');

        // insert content
        $row++;
        $number = 1;

        foreach ($data_pengajuan as $pengajuan) {
            // cetak border
            $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($this->border);

            // insert to cell
            $sheet->getStyle('A' . $row)->applyFromArray($this->center);
            $sheet->setCellValue('A' . $row, $number++);

            $sheet->getStyle('B' . $row)->applyFromArray($this->center);
            $sheet->getCell('B' . $row)->setValueExplicit($pengajuan->nim, DataType::TYPE_STRING);

            $sheet->setCellValue('C' . $row, $pengajuan->nama);

            $sheet->setCellValue('D' . $row, $pengajuan->jenjang_prodi . ' ' . $pengajuan->nama_prodi);

            $sheet->getStyle('E' . $row)->applyFromArray($this->center);
            $sheet->setCellValue('E' . $row, $pengajuan->kelompok);

            $sheet->setCellValue('F' . $row, number_format($pengajuan->nominal_ukt, 0));
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode(self::FORMAT_ACCOUNTING_IDR);

            $sheet->setCellValue('G' . $row, $pengajuan->jenis);

            // $sheet->getStyle('H' . $row)->applyFromArray($this->center);
            // $sheet->setCellValue('H' . $row, ' ');

            // $sheet->getStyle('I' . $row)->applyFromArray($this->center);
            // $sheet->setCellValue('I' . $row, $pengajuan->status ?? '');

            $row++;
        }

        // write to file
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PenerimaDispensasi_' . $semester . '_' . $kode_prodi . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        die;
    }

    // cetak penerima dispensasi dalam bentuk pdf
    private function _pdfPenerimaDispensasi($data_pengajuan, $semester, $kode_prodi)
    {

        $pdf = PDF::loadview('print.pdf_penerima_dispensasi', ['pengajuan' => $data_pengajuan])->setPaper('a4', 'portrait');


        return $pdf->stream('penerima_dispensasi' . $semester . $kode_prodi);
    }
}
