<?php

namespace App\Filament\Resources\Penerimas\Schemas;

use App\Models\Penerima;
use App\Models\KodeSurat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class PenerimaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal_terima')
                    ->required(),
                DatePicker::make('tanggal_surat')
                    ->required(),
                TextInput::make('no_urut')
                    ->required()
                    ->numeric(),
                TextInput::make('no_surat')
                    ->required(),
                TextInput::make('banyak_surat')
                    ->required()
                    ->numeric(),
                Select::make('direktorat_id')
                    ->label('Unit Pengolah')
                    ->relationship('unitPengolah', 'direktorat')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('kode_id')
                    ->label('Kode Surat')
                    ->relationship('kodeSurat', 'kode')
                    ->getOptionLabelFromRecordUsing(fn (KodeSurat $record) =>
                        $record->kode . ' - ' . $record->index
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('pengirim')
                    ->label('Pengirim')
                    ->required()
                    ->datalist(
                        Penerima::query()
                            ->select('pengirim')
                            ->distinct()
                            ->pluck('pengirim')
                            ->toArray()
                    ),
                TextInput::make('perihal')
                    ->label('Perihal')
                    ->required()
                    ->datalist(
                        Penerima::query()
                            ->select('perihal')
                            ->distinct()
                            ->pluck('perihal')
                            ->toArray()
                    ),
                TextInput::make('kontak_person')
                    ->required(),
                Select::make('sifat_surat_id')
                    ->label('Sifat Surat')
                    ->relationship('sifatSurat', 'sifat_surat')
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('ringkasan_poko')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('catatan')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('file_upload')
                    ->required(),
                TextInput::make('no_box')
                    ->required(),
                TextInput::make('no_rak')
                    ->required(),
                /*Toggle::make('kirim_ke_pengarah_surat')
                    ->required(),*/
            ]);
    }
}
