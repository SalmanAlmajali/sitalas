<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProposalPrintController;
use App\Http\Controllers\PengendaliPrintController;
use App\Http\Controllers\SuratKeluarPrintController;
use App\Http\Controllers\ReportSuratKeluarController;
use App\Http\Controllers\FileController;

Route::get('/penerimas/{penerima}/file', [FileController::class, 'penerima'])
    ->name('penerimas.file.show');

Route::get('/pengarahs/{pengarah}/file', [FileController::class, 'pengarah'])
    ->name('pengarahs.file.show');

Route::get('/pengendalis/{pengendali}/file', [FileController::class, 'pengendali'])
    ->name('pengendalis.file.show');

Route::get('/tambahsuratkeluars/{tambahSuratKeluar}/file', [FileController::class, 'tambahSuratKeluar'])
    ->name('tambahsuratkeluars.file.show');

Route::get('/sopdapproves/{sopdApprove}/file', [FileController::class, 'sopdApprove'])
    ->name('sopdapproves.file.show');

Route::get('/reporttrackings/{reportTracking}/file', [FileController::class, 'reportTracking'])
    ->name('reporttrackings.file.show');
    
Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/', '/admin');
Route::middleware(['auth'])->group(function () {
Route::get('/report-proposal/export', [ProposalPrintController::class, 'export'])
    ->name('report.proposal.export');
Route::get('/report-proposal/print', [ProposalPrintController::class, 'print'])
    ->name('report.proposal.print');
Route::get('/pengendali/{id}/print', [PengendaliPrintController::class, 'print'])
    ->name('pengendali.print');
Route::get('/suratkeluar/{id}/print', [SuratKeluarPrintController::class, 'print'])
    ->name('suratkeluar.print');
Route::get('/report-surat-keluar/print', [ReportSuratKeluarController::class, 'print'])
    ->name('report.surat-keluar.print');
Route::get('/report-surat-keluar/export', [ReportSuratKeluarController::class, 'export'])
    ->name('report.surat-keluar.export');
});