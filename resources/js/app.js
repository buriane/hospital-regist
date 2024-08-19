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
    let dokterOptions;

    function getDayName(date) {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return days[date.getDay()];
    }

    function initializeDokterOptions() {
        // Store all doctor options in a fragment
        const fragment = document.createDocumentFragment();
        dokterOptions = Array.from(dokterSelect.querySelectorAll('option:not([value=""])'));
        dokterOptions.forEach(option => fragment.appendChild(option));

        // Remove all options except the default one
        while (dokterSelect.options.length > 1) {
            dokterSelect.remove(1);
        }

        // Store the fragment for later use
        dokterSelect.dokterOptionsFragment = fragment;
    }

    function updateDokterOptions() {
        const selectedPoliklinik = poliklinikSelect.value;
        const selectedDate = tanggalInput.value;

        // Reset doctor select to default state
        while (dokterSelect.options.length > 1) {
            dokterSelect.remove(1);
        }

        // If no poliklinik is selected, don't show any doctors
        if (!selectedPoliklinik) {
            return;
        }

        const selectedDay = getDayName(new Date(selectedDate));

        // Create a new fragment to hold the filtered options
        const fragment = document.createDocumentFragment();

        dokterOptions.forEach((option) => {
            const clonedOption = option.cloneNode(true);
            if (option.dataset.special === "true" && 
                option.dataset.tanggal === selectedDate && 
                option.dataset.poliklinik === selectedPoliklinik &&
                parseInt(option.dataset.kuota) > 0) {
                fragment.appendChild(clonedOption);
            } else {
                const specialDates = JSON.parse(option.dataset.specialDates || '[]');
                if (option.dataset.special !== "true" && 
                    !specialDates.includes(selectedDate + '-' + option.value) &&
                    option.dataset.hari === selectedDay && 
                    option.dataset.poliklinik === selectedPoliklinik && 
                    parseInt(option.dataset.kuota) > 0) {
                    fragment.appendChild(clonedOption);
                }
            }
        });

        // Append the filtered options to the select element
        dokterSelect.appendChild(fragment);
    }

    poliklinikSelect.addEventListener("change", updateDokterOptions);
    tanggalInput.addEventListener("change", updateDokterOptions);

    // Initialize dokter options
    initializeDokterOptions();

    if (tanggalInput.value && poliklinikSelect.value) {
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