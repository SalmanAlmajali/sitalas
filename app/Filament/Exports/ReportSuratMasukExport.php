<?php

namespace App\Filament\Exports;

use App\Models\Penerima;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportSuratMasukExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents
{
    public function __construct(
        protected array $filters = []
    ) {}

    public function collection()
    {
        return $this->getQuery()->get();
    }

    public function headings(): array
    {
        return [
            'Tgl Masuk',
            'Tgl Surat',
            'Pengirim',
            'Unit Pengolah',
            'Sifat Surat',
            'Kode',
            'Perihal',
            'No Surat',
            'No Box',
            'No Rak',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal_terima ? Date::dateTimeToExcel($row->tanggal_terima) : null,
            $row->tanggal_surat ? Date::dateTimeToExcel($row->tanggal_surat) : null,
            $row->pengirim,
            $row->unitPengolah?->direktorat,
            $row->sifatSurat?->sifat_surat,
            $row->kodeSurat?->kode,
            $row->perihal,
            $row->no_surat,
            $row->no_box,
            $row->no_rak,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $fullRange = "A1:{$highestColumn}{$highestRow}";

                // Header style
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Border semua cell
                $sheet->getStyle($fullRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                    ],
                ]);

                // Autofilter
                $sheet->setAutoFilter($fullRange);

                // Freeze header
                $sheet->freezePane('A2');

                // Wrap text kolom perihal
                $sheet->getStyle("G2:G{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                // Rata tengah untuk beberapa kolom
                $sheet->getStyle("A2:B{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("H2:J{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tinggi row header
                $sheet->getRowDimension(1)->setRowHeight(24);
            },
        ];
    }

    protected function getQuery(): Builder
    {
        return Penerima::query()
            ->with([
                'unitPengolah',
                'kodeSurat',
                'sifatSurat',
            ])
            ->when(
                data_get($this->filters, 'tanggal_dari.tanggal_dari'),
                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '>=', $date)
            )
            ->when(
                data_get($this->filters, 'tanggal_sampai.tanggal_sampai'),
                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '<=', $date)
            )
            ->when(
                data_get($this->filters, 'unit_pengolah.direktorat_id'),
                fn (Builder $query, $id): Builder => $query->where('direktorat_id', $id)
            )
            ->when(
                data_get($this->filters, 'sifat_surat.sifat_surat_id'),
                fn (Builder $query, $id): Builder => $query->where('sifat_surat_id', $id)
            )
            ->orderByDesc('tanggal_terima');
    }
}