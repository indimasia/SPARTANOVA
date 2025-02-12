<x-filament-widgets::widget>
    <x-filament::section>
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold">Total Poin</h3>
            <p class="text-2xl font-bold">{{ number_format($this->getTotalPoints(), 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('filament.pengiklan.resources.topups.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition" style="color: orange; border: 1px solid orange;">
            Top Up
        </a>
    </div>
    </x-filament::section>
</x-filament-widgets::widget>
