<?php

namespace App\Filament\Resources\Pengarahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Pengarah;
use App\Models\Pengendali;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;

class PengarahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                /*TextColumn::make('penerima_id')
                    ->numeric()
                    ->sortable(),*/
                TextColumn::make('tanggal_terima')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_surat')
                    ->date()
                    ->sortable(),
                /*TextColumn::make('no_urut')
                    ->numeric()
                    ->sortable(),*/
                TextColumn::make('no_surat')
                    ->searchable(),
                /*TextColumn::make('banyak_surat')
                    ->numeric()
                    ->sortable(),*/
                TextColumn::make('perihal')
                    ->searchable(),
                TextColumn::make('sifatSurat.sifat_surat')
                    ->label('Sifat Surat')
                    ->sortable(),
                TextColumn::make('file_upload')
                    ->label('File upload')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->url(fn ($record) => route('pengarahs.file.show', ['pengarah' => $record->getKey()]))
                    ->openUrlInNewTab(),
                TextColumn::make('unitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->sortable(),
                TextColumn::make('kodeSurat.kode')
                    ->label('Kode Surat')
                    ->sortable(),
                TextColumn::make('kodeSurat.index')
                    ->label('Indeks')
                    ->sortable(),
                /*TextColumn::make('pengirim')
                    ->searchable(),*/
                
                /*TextColumn::make('kontak_person')
                    ->searchable(),
                
                TextColumn::make('no_box')
                    ->searchable(),
                TextColumn::make('no_rak')
                    ->searchable(),*/
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
                ViewAction::make(),
                EditAction::make(),
                Action::make('kirim_ke_pengendali')
                    ->label('Kirim ke Pengendali')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim ke Pengendali')
                    ->modalDescription('Data akan dipindahkan ke Pengendali dan hilang dari daftar Pengarah.')
                    ->action(function (Pengarah $record) {

                        DB::transaction(function () use ($record) {

                            // proteksi: sekali kirim saja (berdasarkan penerima_id unik)
                            if (Pengendali::where('penerima_id', $record->penerima_id)->exists()) {
                                throw new \RuntimeException('Data ini sudah pernah dikirim ke Pengendali.');
                            }

                            Pengendali::create([
                                'penerima_id'     => $record->penerima_id,
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

                                'status'          => 'dikirim',
                                'dikirim_pada'    => now(),
                                'pengarah_id'     => $record->id,
                            ]);

                            // simpan jejak status di pengarah, lalu hilangkan dari list (soft delete)
                            $record->update(['status' => 'dikirim']);
                            $record->delete();
                        });

                        Notification::make()
                            ->title('Berhasil dikirim ke Pengendali')
                            ->success()
                            ->send();
                    })
                    ->failureNotification(
                        Notification::make()
                            ->title('Gagal mengirim ke Pengendali')
                            ->danger()
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
