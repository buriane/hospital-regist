<x-filament::widget>
    <x-filament::card>
        <div x-data="clock()" x-init="startClock()">
            <p class="text-2xl font-light text-gray-700 dark:text-gray-300" x-text="time"></p>
            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="date"></p>
        </div>
    </x-filament::card>
</x-filament::widget>

<script>
function clock() {
    return {
        time: '',
        date: '',
        startClock() {
            setInterval(() => {
                let now = new Date();
                this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false }).replace('.', ':');
                this.date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            }, 1000);
        }
    }
}
</script>