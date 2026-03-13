<x-filament-panels::page>
    <style>
        .jumlah-surat-filter-only .fi-ta-table,
        .jumlah-surat-filter-only .fi-ta-content,
        .jumlah-surat-filter-only .fi-ta-empty-state,
        .jumlah-surat-filter-only table[aria-busy],
        .jumlah-surat-filter-only .fi-ta-header-cell,
        .jumlah-surat-filter-only tbody {
            display: none !important;
        }

        .jumlah-surat-filter-only .fi-ta-ctn,
        .jumlah-surat-filter-only .fi-ta-header {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .report-wrapper {
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.02);
        }

        .report-header,
        .report-row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .report-header {
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
        }

        .report-cell {
            display: table-cell;
            padding: 12px 14px;
            vertical-align: middle;
        }

        .report-cell-toggle {
            width: 56px;
            text-align: center;
        }

        .report-cell-name {
            width: auto;
        }

        .report-cell-total {
            width: 180px;
            text-align: center;
        }

        .report-group {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .report-subtable-wrap {
            padding: 0 16px 16px 16px;
            background: rgba(255, 255, 255, 0.02);
        }

        .report-subtable {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .report-subtable th,
        .report-subtable td {
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        .report-subtable th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 700;
        }

        .report-subtable td.nowrap,
        .report-subtable th.nowrap {
            white-space: nowrap;
        }

        .toggle-btn {
            width: 28px;
            height: 28px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 6px;
            background: transparent;
            color: white;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        .empty-box {
            border: 1px dashed rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 16px;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>

    <div class="space-y-6">
        <div class="jumlah-surat-filter-only">
            {{ $this->table }}
        </div>

        @if (! $this->hasFilterTanggal())
            <div class="empty-box">
                Pilih tanggal dari tombol filter di kanan atas, lalu klik <strong>Apply filters</strong>.
            </div>
        @else
            <div class="report-wrapper">
                <div class="report-header">
                    <div class="report-cell report-cell-toggle"></div>
                    <div class="report-cell report-cell-name">Direktorat</div>
                    <div class="report-cell report-cell-total">Jumlah Surat</div>
                </div>

                @forelse ($this->groupedSurat as $group)
                    <div class="report-group" x-data="{ open: false }">
                        <div class="report-row">
                            <div class="report-cell report-cell-toggle">
                                <button type="button" class="toggle-btn" @click="open = !open">
                                    <span x-show="!open">+</span>
                                    <span x-show="open">−</span>
                                </button>
                            </div>

                            <div class="report-cell report-cell-name">
                                {{ $group->direktorat }}
                            </div>

                            <div class="report-cell report-cell-total">
                                {{ $group->jumlah_surat }}
                            </div>
                        </div>

                        <div x-show="open" x-collapse class="report-subtable-wrap">
                            <table class="report-subtable">
                                <thead>
                                    <tr>
                                        <th class="nowrap" style="width: 120px;">Tgl Surat</th>
                                        <th style="width: 220px;">Pengirim</th>
                                        <th style="width: 100px;">No Surat</th>
                                        <th style="width: 200px;">Perihal</th>
                                        <th class="nowrap" style="width: 140px;">Kode Surat</th>
                                        <th class="nowrap" style="width: 140px;">Sifat Surat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($group->items as $item)
                                        <tr>
                                            <td class="nowrap">
                                                {{ $item->tanggal_surat?->format('d M Y') }}
                                            </td>
                                            <td>
                                                {{ $item->pengirim }}
                                            </td>
                                            <td>
                                                {{ $item->no_surat }}
                                            </td>
                                            <td>
                                                {{ $item->perihal }}
                                            </td>
                                            <td class="nowrap">
                                                {{ $item->kodeSurat?->kode }}
                                            </td>
                                            <td class="nowrap">
                                                {{ $item->sifatSurat?->sifat_surat }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center; color: rgba(255,255,255,.65);">
                                                No data to display
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div style="padding: 16px; color: rgba(255,255,255,.7);">
                        Tidak ada data.
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</x-filament-panels::page>