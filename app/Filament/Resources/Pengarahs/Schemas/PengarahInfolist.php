<?php

namespace App\Filament\Resources\Pengarahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PengarahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Detail Surat')
                    ->description('Informasi utama surat masuk')
                    ->columns(12)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('tanggal_surat')
                            ->label('Tanggal Surat')
                            ->date('d F Y')
                            ->columnSpan(4),

                        TextEntry::make('no_urut')
                            ->label('No Urut')
                            ->badge()
                            ->columnSpan(2),

                        TextEntry::make('no_surat')
                            ->label('Nomor Surat')
                            ->copyable()
                            ->columnSpan(4),

                        TextEntry::make('kepada')
                            ->label('Kepada')
                            ->placeholder('-')
                            ->columnSpan(6),

                        TextEntry::make('kontak_person')
                            ->label('Kontak Person')
                            ->placeholder('-')
                            ->columnSpan(6),

                        TextEntry::make('perihal')
                            ->label('Perihal')
                            ->placeholder('-')
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Klasifikasi')
                    ->description('Informasi klasifikasi dan pengolahan surat')
                    ->columns(12)
                    ->columnSpanFull()
                    ->collapsed()
                    ->components([
                        TextEntry::make('unitPengolah.direktorat')
                            ->label('Direktorat')
                            ->columnSpan(4),

                        TextEntry::make('Kode.kode')
                            ->label('Kode')
                            ->columnSpan(4),

                        TextEntry::make('sifat.sifat_surat')
                            ->label('Sifat Surat')
                            ->columnSpan(4),
                    ]),
            ]);
    }
}
