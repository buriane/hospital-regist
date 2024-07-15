import './bootstrap';
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

// Mobile Burger Menu
// document.getElementById('menu-toggle').addEventListener('click', function() {
//     var menu = document.getElementById('menu');
//     menu.classList.toggle('hidden');
// });

// Date Picker
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('tanggal_kunjungan');
    const today = new Date();
    const tomorrow = new Date();
    const nextWeek = new Date();
    
    tomorrow.setDate(today.getDate() + 1);
    nextWeek.setDate(today.getDate() + 7);

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    dateInput.min = formatDate(tomorrow);
    dateInput.max = formatDate(nextWeek);
});