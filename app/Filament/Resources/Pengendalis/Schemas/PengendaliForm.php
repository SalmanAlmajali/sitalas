<?php

namespace App\Filament\Resources\Pengendalis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class PengendaliForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                /*TextInput::make('penerima_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal_terima')
                    ->required(),
                DatePicker::make('tanggal_surat')
                    ->required(),
                TextInput::make('no_urut')
                    ->required()
                    ->numeric(),*/
                TextInput::make('no_surat')
                    ->required(),
                /*TextInput::make('banyak_surat')
                    ->required()
                    ->numeric(),
                TextInput::make('direktorat_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kode_id')
                    ->required()
                    ->numeric(),
                TextInput::make('pengirim')
                    ->required(),*/
                TextInput::make('perihal')
                    ->required(),
                /*TextInput::make('kontak_person')
                    ->required(),
                TextInput::make('sifat_surat_id')
                    ->required()
                    ->numeric(),*/
                Textarea::make('ringkasan_poko')
                    ->required()
                    ->columnSpanFull(),
                /*Textarea::make('catatan')
                    ->required()
                    ->columnSpanFull(),*/
                FileUpload::make('file_upload')
                    ->required(),
                /*TextInput::make('no_box')
                    ->required(),
                TextInput::make('no_rak')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('baru'),
                DateTimePicker::make('dikirim_pada'),
                TextInput::make('pengarah_id')
                    ->numeric(),*/
            ]);
    }
}
