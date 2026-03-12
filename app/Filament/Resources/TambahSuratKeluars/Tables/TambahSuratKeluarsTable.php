<?php

namespace App\Filament\Resources\TambahSuratKeluars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;

class TambahSuratKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_surat')
                    ->date()
                    ->sortable(),
                TextColumn::make('unitPengolah.direktorat')
                    ->label('Unit Pegolah')
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
                /*TextColumn::make('Kode.kode')
                    ->label('Kode Surat')
                    ->sortable(),
                TextColumn::make('Sifat.sifat_surat')
                    ->label('Sifat Surat')
                    ->sortable(),
                
                TextColumn::make('kontak_person')
                    ->searchable(),*/
                TextColumn::make('kepada')
                    ->searchable(),
               TextColumn::make('upload_file')
                    ->label('File upload')
                    ->formatStateUsing(fn ($state) => filled($state) ? basename($state) : '-')
                    ->url(fn ($record) => filled($record->upload_file)
                        ? route('tambahsuratkeluars.file.show', [
                            'tambahSuratKeluar' => $record->getKey(),
                        ])
                        : null
                    )
                    ->openUrlInNewTab(),
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
            ])
            ->actions([
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('suratkeluar.print', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
