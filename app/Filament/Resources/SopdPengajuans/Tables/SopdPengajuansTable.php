<?php

namespace App\Filament\Resources\SopdPengajuans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use App\Models\SopdApprove;

class SopdPengajuansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_surat')
                    ->date()
                    ->sortable(),

                TextColumn::make('UnitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->sortable(),

                TextColumn::make('no_surat')
                    ->searchable(),

                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable(),

                TextColumn::make('Klasifikasi.klasifikasi')
                    ->label('Klasifikasi Surat')
                    ->sortable(),

                TextColumn::make('no_urut')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('kepada')
                    ->searchable(),

                TextColumn::make('upload_file')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->default('belum request')
                    ->sortable(),
                    

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
            ->actions([
                ViewAction::make(),

                EditAction::make()
                    ->visible(fn ($record) => ! $record->is_requested),

                Action::make('request')
                    ->label('Request')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! $record->is_requested)
                    ->action(function ($record) {
                        if ($record->is_requested) {
                            return;
                        }

                        SopdApprove::create([
                            'tambah_surat_keluar_id' => $record->id,
                            'tanggal_surat' => $record->tanggal_surat,
                            'klasifikasi_id' => $record->klasifikasi_id,
                            'no_urut' => $record->no_urut,
                            'kode_id' => $record->kode_id,
                            'no_surat' => $record->no_surat,
                            'sifat_surat_id' => $record->sifat_surat_id,
                            'perihal' => $record->perihal,
                            'direktorat_id' => $record->direktorat_id,
                            'kontak_person' => $record->kontak_person,
                            'kepada' => $record->kepada,
                            'keterangan' => $record->keterangan,
                            'upload_file' => $record->upload_file,
                            'lampiran' => $record->lampiran,
                        ]);

                        $record->update([
                            'is_requested' => true,
                            'status' => 'pending',
                            'alasan_penolakan' => null,
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}