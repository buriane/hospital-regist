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
    today.setHours(0, 0, 0, 0); 
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);
    const lastDay = new Date(today);
    lastDay.setDate(today.getDate() + 8);

    const formatDate = (date) => {
        return date.toISOString().split('T')[0];
    };

    dateInput.min = formatDate(tomorrow);
    dateInput.max = formatDate(lastDay);

    dateInput.addEventListener("input", function() {
        const selectedDate = new Date(this.value + "T00:00:00");
        if (selectedDate < tomorrow || selectedDate >= lastDay) {
            this.value = "";
            alert("Mohon pilih tanggal antara besok atau 7 hari ke depan.");
        }
    });
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
            if (option.dataset.poliklinik === selectedPoliklinik && 
                option.dataset.tanggal === selectedTanggal && 
                parseInt(option.dataset.kuota) > 0) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        });

        // Reset pilihan dokter
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

    // Initial update
    updateDokterOptions();
});

// Check Patient
document.addEventListener('DOMContentLoaded', function() {
    const nomor_rm = document.getElementById('nomor_rm');
    const tanggal_lahir = document.getElementById('tanggal_lahir');
    const patientData = document.getElementById('patientData');

    nomor_rm.addEventListener('input', function() {
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6);
        }
    });

    function checkPatient() {
        if (nomor_rm.value && tanggal_lahir.value) {
            fetch(`/check-patient?nomor_rm=${nomor_rm.value}&tanggal_lahir=${tanggal_lahir.value}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        // patientData.classList.remove('hidden');
                        document.getElementById('id_pasien').value = data.id_pasien;
                        document.getElementById('nama_pasien_baru').value = data.nama_pasien;
                        document.getElementById('tempat_lahir_baru').value = data.tempat_lahir;
                        document.getElementById('tanggal_lahir_baru').value = data.tanggal_lahir;
                        if (data.jenis_kelamin == "Perempuan") {
                            document.querySelector('input[name="jenis_kelamin"][value="Perempuan"]')
                                .checked = true;
                        } else {
                            document.querySelector('input[name="jenis_kelamin"][value="Laki-laki"]')
                                .checked = true;

                        }
                        document.getElementById('alamat_baru').value = data.alamat;
                        document.getElementById('nomor_telepon_baru').value = data.nomor_telepon;
                        document.getElementById('email_baru').value = data.email;
                        document.getElementById('nomor_kartu_baru').value = data.nomor_kartu;
                    } else {
                        // patientData.classList.add('hidden');
                    }
                });
        }
    }
    nomor_rm.addEventListener('blur', checkPatient);
    tanggal_lahir.addEventListener('change', checkPatient);
});