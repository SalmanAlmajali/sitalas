<?php

namespace App\Filament\Resources\SopdApproves\Tables;

use App\Models\KodeSurat;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\TambahSuratKeluar;
use App\Models\ListBiro;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\DB;

class SopdApprovesTable
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

                TextColumn::make('Kode.kode')
                    ->label('Kode Surat')
                    ->sortable()
                    ->default('-'),

                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable(),

                TextColumn::make('kepada')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('kontak_person')
                    ->label('No Hp')
                    ->searchable(),

                TextColumn::make('upload_file')
                    ->label('File upload')
                    ->formatStateUsing(fn($state) => filled($state) ? basename($state) : '-')
                    ->url(
                        fn($record) => filled($record->upload_file)
                            ? route('sopdapproves.file.show', [
                                'sopdApprove' => $record->getKey(),
                            ])
                            : null
                    )
                    ->openUrlInNewTab(),

                TextColumn::make('Klasifikasi.klasifikasi')
                    ->label('Klasifikasi')
                    ->sortable(),

                TextColumn::make('tambahSuratKeluar.status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                        'secondary' => 'belum request',
                    ]),
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

                Action::make('terima')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->tambahSuratKeluar?->status === 'pending')
                    ->schema([
                        Select::make('kode_id')
                            ->label('Kode Surat')
                            ->relationship('Kode', 'kode')
                            ->getOptionLabelFromRecordUsing(
                                fn(KodeSurat $record) =>
                                $record->kode . ' - ' . $record->index
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('no_surat')
                            ->required(),
                        Select::make('klasifikasi_id')
                            ->label('Klasifikasi Surat')
                            ->relationship('Klasifikasi', 'klasifikasi')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('no_urut')
                            ->required()
                            ->default(function ($record) {
                                $nomorBaru = TambahSuratKeluar::generateNoSurat(
                                    $record->kode_id,
                                    $record->tanggal_surat
                                );

                                return $nomorBaru['no_urut'];
                            }),
                    ])
                    ->action(function ($record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $surat = TambahSuratKeluar::find($record->tambah_surat_keluar_id);

                            if (! $surat) {
                                Notification::make()
                                    ->title('Data surat keluar tidak ditemukan')
                                    ->danger()
                                    ->send();

                                return;
                            }
                 
                            ListBiro::create([
                                'tambah_surat_keluar_id' => $record->tambah_surat_keluar_id,
                                'tanggal_surat' => $record->tanggal_surat,
                                'klasifikasi_id' => $data['klasifikasi_id'],
                                'no_urut' => $data['no_urut'],
                                'kode_id' => $data['kode_id'],
                                'no_surat' => $data['no_surat'],
                                'sifat_surat_id' => $record->sifat_surat_id,
                                'perihal' => $record->perihal,
                                'direktorat_id' => $record->direktorat_id,
                                'kontak_person' => $record->kontak_person,
                                'kepada' => $record->kepada,
                                'keterangan' => $record->keterangan,
                                'upload_file' => $record->upload_file,
                                'lampiran' => $record->lampiran,
                            ]);



                            $surat->update([
                                'klasifikasi_id' => $data['klasifikasi_id'],
                                'no_urut' => $data['no_urut'],
                                'kode_id' => $data['kode_id'],
                                'no_surat' => $data['no_surat'],
                                'status' => 'diterima',
                                'alasan_penolakan' => null,
                            ]);

                            $record->delete();
                        });

                        Notification::make()
                            ->title('Pengajuan berhasil diterima dan dipindahkan ke List Biro')
                            ->success()
                            ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->tambahSuratKeluar?->status === 'pending')
                    ->form([
                        Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->default('surat ini harus di perbaiki')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $surat = TambahSuratKeluar::find($record->tambah_surat_keluar_id);

                            if (! $surat) {
                                Notification::make()
                                    ->title('Data surat keluar tidak ditemukan')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $surat->update([
                                'status' => 'ditolak',
                                'alasan_penolakan' => $data['alasan_penolakan'],
                            ]);

                            $record->delete();
                        });

                        Notification::make()
                            ->title('Pengajuan berhasil ditolak')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
