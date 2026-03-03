<?php

namespace App\Filament\Resources\Penerimas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Table;
use App\Models\Pengarah;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;


class PenerimasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_terima')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_surat')
                    ->date()
                    ->sortable(),
                TextColumn::make('no_urut')
                    ->numeric()
                    ->sortable(),
                /*TextColumn::make('no_surat')
                    ->searchable(),
                TextColumn::make('banyak_surat')
                    ->numeric()
                    ->sortable(),*/
                TextColumn::make('file_upload')
                    ->label('File upload')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->url(fn ($record) => route('penerimas.file.show', ['penerima' => $record->getKey()]))
                    ->openUrlInNewTab(),
                TextColumn::make('pengirim')
                    ->searchable(),
                TextColumn::make('perihal')
                    ->searchable(),
                TextColumn::make('unitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->sortable(),
                TextColumn::make('kodeSurat.index')
                    ->label('Indeks')
                    ->sortable(),
                
                
                /*TextColumn::make('kontak_person')
                    ->searchable(),
                TextColumn::make('sifatSurat.sifat_surat')
                    ->label('Sifat Surat')
                    ->sortable(),
               
                TextColumn::make('no_box')
                    ->searchable(),
                TextColumn::make('no_rak')
                    ->searchable(),
                IconColumn::make('kirim_ke_pengarah_surat')
                    ->boolean(),*/
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('kirimKePengarah')
                ->label('Kirim ke Pengarah')
                ->icon('heroicon-o-paper-airplane')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->pengarah()->doesntExist())
                ->action(function ($record) {
                    DB::transaction(function () use ($record) {
                        if ($record->pengarah()->exists()) {
                            return; // safety anti double klik
                        }

                        Pengarah::create([
                            'penerima_id'     => $record->id,
                            'tanggal_terima'  => $record->tanggal_terima,
                            'tanggal_surat'   => $record->tanggal_surat,
                            'no_urut'         => $record->no_urut,
                            'no_surat'        => $record->no_surat,
                            'banyak_surat'    => $record->banyak_surat,
                            'direktorat_id'   => $record->direktorat_id,
                            'kode_id'         => $record->kode_id,
                            'pengirim'        => $record->pengirim,
                            'perihal'         => $record->perihal,
                            'kontak_person'   => $record->kontak_person,
                            'sifat_surat_id'  => $record->sifat_surat_id,
                            'ringkasan_poko'  => $record->ringkasan_poko,
                            'catatan'         => $record->catatan,
                            'file_upload'     => $record->file_upload,
                            'no_box'          => $record->no_box,
                            'no_rak'          => $record->no_rak,
                        ]);
                    });
                })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
            
    }
}
