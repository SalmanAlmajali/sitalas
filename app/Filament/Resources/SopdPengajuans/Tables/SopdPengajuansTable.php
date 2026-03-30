<?php

namespace App\Filament\Resources\SopdPengajuans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\Action;
use App\Models\SopdApprove;
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isNull;

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
                    ->searchable()
                    ->default('-'),

                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable(),

                TextColumn::make('kepada')
                    ->searchable(),

                TextColumn::make('kontak_person')
                    ->label('No Hp')
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

                TextColumn::make('user.name')
                    ->label('Diinput Oleh')
                    ->badge()
                    ->color('info'),

                IconColumn::make('is_sopd_req')
                    ->label('Sopd Req')
                    ->boolean(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ?: 'belum request')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                        'secondary' => 'belum request',
                    ]),
                
                TextColumn::make('alasan_penolakan')
                    ->label('Alasan Penolakan')
                    ->wrap()
                    ->color('danger')
                    ->formatStateUsing(fn ($state, $record) => $record->status === 'ditolak' ? $state : '-'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Data'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),

                EditAction::make()
                    ->visible(fn ($record) => ! $record->is_requested),

                Action::make('request')
                    ->label('Minta Persetujuan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! $record->is_requested && $record->user_id === auth()->id())
                    ->action(function ($record) {
                        if ($record->is_requested || $record->user_id !== auth()->id()) {
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