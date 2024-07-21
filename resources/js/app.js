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
    const tanggal = document.getElementById("tanggal_kunjungan_baru");
    const dokterSelect = document.getElementById("dokter");
    const dokterOptions = dokterSelect.querySelectorAll('option:not([value=""])');
    var selectedPoliklinik = poliklinikSelect.value;
    var selectedTanggal = tanggal.value;

    function updateDokterOptions() {
        dokterOptions.forEach((option) => {
            if (
                option.dataset.poliklinik === selectedPoliklinik &&
                option.dataset.tanggal === selectedTanggal &&
                parseInt(option.dataset.kuota) > 0
            ) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        });

        dokterSelect.value = "";
    }

    poliklinikSelect.addEventListener("change", function () {
        selectedPoliklinik = this.value;
        updateDokterOptions();
    });

    tanggal.addEventListener("change", function () {
        selectedTanggal = this.value;
        updateDokterOptions();
    });

    poliklinikSelect.dispatchEvent(new Event("change"));
    tanggal.dispatchEvent(new Event("change"));
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