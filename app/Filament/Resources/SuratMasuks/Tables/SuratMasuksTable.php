<?php

namespace App\Filament\Resources\SuratMasuks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuratMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_terima')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_surat')
                    ->date()
                    ->sortable(),
                TextColumn::make('no_urut')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('no_surat')
                    ->numeric(),
                TextColumn::make('upload_file')
                    ->searchable(),
                TextColumn::make('pengirim')
                    ->searchable(),
                TextColumn::make('perihal')
                    ->searchable(),
                TextColumn::make('unitPengolah.direktorat')
                    ->label('Direktorat')
                    ->sortable(),
                TextColumn::make('kodeSurat.kode')
                    ->label('Kode Surat')
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
