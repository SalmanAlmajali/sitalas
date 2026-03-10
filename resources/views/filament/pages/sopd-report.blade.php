<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section heading="Filter Tanggal">
            {{ $this->form }}
        </x-filament::section>

        <x-filament::section heading="Data SOPD Report">
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>