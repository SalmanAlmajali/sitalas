<?php

namespace App\Filament\Resources\SopdApproves\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SopdApproveInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tambah_surat_keluar_id')
                    ->numeric(),
                TextEntry::make('tanggal_surat')
                    ->date(),
                TextEntry::make('klasifikasi_id')
                    ->numeric(),
                TextEntry::make('no_urut')
                    ->numeric(),
                TextEntry::make('kode_id')
                    ->numeric(),
                TextEntry::make('no_surat'),
                TextEntry::make('sifat_surat_id')
                    ->numeric(),
                TextEntry::make('perihal')
                    ->columnSpanFull(),
                TextEntry::make('direktorat_id')
                    ->numeric(),
                TextEntry::make('kontak_person'),
                TextEntry::make('kepada'),
                TextEntry::make('keterangan')
                    ->columnSpanFull(),
                TextEntry::make('upload_file'),
                TextEntry::make('lampiran')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
