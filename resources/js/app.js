import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Mobile Burger Menu
// document.getElementById('menu-toggle').addEventListener('click', function() {
//     var menu = document.getElementById('menu');
//     menu.classList.toggle('hidden');
// });

// Date Picker
document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("tanggal_kunjungan_baru");
    const today = new Date();
    const tomorrow = new Date();
    const nextWeek = new Date();

    tomorrow.setDate(today.getDate() + 1);
    nextWeek.setDate(today.getDate() + 7);

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        return `${year}-${month}-${day}`;
    };

    dateInput.min = formatDate(tomorrow);
    dateInput.max = formatDate(nextWeek);
});

// Poli Dokter Filter
document.addEventListener("DOMContentLoaded", function () {
    const poliklinikSelect = document.getElementById("poliklinik");
    const tanggalInput = document.getElementById("tanggal_kunjungan_baru");
    const dokterSelect = document.getElementById("dokter");
    const dokterOptions = dokterSelect.querySelectorAll('option:not([value=""])');

    function getDayName(date) {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return days[date.getDay()];
    }

    function updateDokterOptions() {
        const selectedPoliklinik = poliklinikSelect.value;
        const selectedDate = tanggalInput.value;
        const selectedDay = getDayName(new Date(selectedDate));

        // First, hide all options
        dokterOptions.forEach(option => option.style.display = "none");

        // Show special schedules for the selected date with quota > 0
        dokterOptions.forEach((option) => {
            if (option.dataset.special === "true" && 
                option.dataset.tanggal === selectedDate && 
                option.dataset.poliklinik === selectedPoliklinik &&
                parseInt(option.dataset.kuota) > 0) {
                option.style.display = "";
            }
        });

        // Show regular schedules if there's no special schedule for that specific doctor
        dokterOptions.forEach((option) => {
            const specialDates = JSON.parse(option.dataset.specialDates || '[]');
            if (option.dataset.special !== "true" && 
                !specialDates.includes(selectedDate + '-' + option.value) &&
                option.dataset.hari === selectedDay && 
                option.dataset.poliklinik === selectedPoliklinik && 
                parseInt(option.dataset.kuota) > 0) {
                option.style.display = "";
            }
        });

        dokterSelect.value = "";
    }

    poliklinikSelect.addEventListener("change", updateDokterOptions);
    tanggalInput.addEventListener("change", updateDokterOptions);

    if (tanggalInput.value) {
        updateDokterOptions();
    }
});

// Load Content
document.addEventListener("DOMContentLoaded", function () {
    const input_id = document.getElementById("id_pasien");
    var input_value = input_id.value;

    input_id.addEventListener("onchange", function () {
        input_value = this.value;
        // document.querySelector("[x-data]").__x.$data.id_pasien = input_value;
        // console.log(Alpine.data("id_pasien", input_value));
        Alpine.data("id_pasien", input_value);

        // console.log(Alpine.data("id_pasien", input_value));
        console.log(input_value);
    });

    input_id.dispatchEvent(new Event("onchange"));
});

// Loading Animation
document.addEventListener("DOMContentLoaded", function() {
    const loader = document.getElementById('loader');
    const minLoadTime = 300;
    let loadStartTime;

    function showLoader() {
        loadStartTime = Date.now();
        loader.style.display = 'flex';
    }

    function hideLoader() {
        const currentTime = Date.now();
        const elapsedTime = currentTime - loadStartTime;
        const remainingTime = Math.max(0, minLoadTime - elapsedTime);

        setTimeout(() => {
            loader.style.display = 'none';
        }, remainingTime);
    }

    showLoader(); 

    window.addEventListener('load', function() {
        hideLoader(); 
    });

    document.addEventListener('beforeunload', function() {
        showLoader(); 
    });
});

// Dokter Selection and jam_mulai/jam_selesai Update
document.addEventListener("DOMContentLoaded", function() {
    const dokterSelect = document.getElementById('dokter');
    const jamMulaiInput = document.getElementById('jam_mulai');
    const jamSelesaiInput = document.getElementById('jam_selesai');

    if (dokterSelect && jamMulaiInput && jamSelesaiInput) {
        dokterSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            jamMulaiInput.value = selectedOption.getAttribute('data-jam-mulai');
            jamSelesaiInput.value = selectedOption.getAttribute('data-jam-selesai');
        });
    }
});