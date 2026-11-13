// halaman
document.addEventListener("DOMContentLoaded", function () {

    // client side validation
    const formPegawai = document.getElementById("formPegawai");

    if (formPegawai) {
        formPegawai.addEventListener("submit", function (event) {
            let isValid = true;
            let errorMessage = "";

            // take element input
            const nip = document.getElementById("nip");
            const nama = document.getElementById("nama_lengkap");
            const email = document.getElementById("email");
            const noHp = document.getElementById("no_hp");
            const jabatan = document.getElementById("jabatan");
            const alamat = document.getElementById("alamat");

            // nip harus 3 karakter & belum terdaftar
            if (!nip.value.trim() || nip.value.trim().length < 3) {
                errorMessage += "- NIP wajib diisi minimal 3 karakter.\n";
                nip.classList.add("is-invalid");
                isValid = false;
            } else if (nip.dataset.taken === "true") {
                errorMessage += "- NIP sudah terdaftar di database, gunakan NIP lain.\n";
                nip.classList.add("is-invalid");
                isValid = false;
            } else {
                nip.classList.remove("is-invalid");
            }

            // nama lengkap
            if (!nama.value.trim()) {
                errorMessage += "- Nama lengkap tidak boleh kosong.\n";
                nama.classList.add("is-invalid");
                isValid = false;
            } else {
                nama.classList.remove("is-invalid");
            }

            // format email normal & belum terdaftar
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
                errorMessage += "- Format email tidak valid (contoh: nama@domain.com).\n";
                email.classList.add("is-invalid");
                isValid = false;
            } else if (email.dataset.taken === "true") {
                errorMessage += "- Email sudah terdaftar di database, gunakan email lain.\n";
                email.classList.add("is-invalid");
                isValid = false;
            } else {
                email.classList.remove("is-invalid");
            }

            // no hp 10 angka
            const phoneRegex = /^[0-9+]{10,15}$/;
            if (!noHp.value.trim() || !phoneRegex.test(noHp.value.trim())) {
                errorMessage += "- Nomor HP harus berupa angka (10-15 digit).\n";
                noHp.classList.add("is-invalid");
                isValid = false;
            } else {
                noHp.classList.remove("is-invalid");
            }

            // jabatan 
            if (!jabatan.value.trim()) {
                errorMessage += "- Jabatan tidak boleh kosong.\n";
                jabatan.classList.add("is-invalid");
                isValid = false;
            } else {
                jabatan.classList.remove("is-invalid");
            }

            // alamat
            if (!alamat.value.trim()) {
                errorMessage += "- Alamat wajib diisi.\n";
                alamat.classList.add("is-invalid");
                isValid = false;
            } else {
                alamat.classList.remove("is-invalid");
            }

            // salah, batal kirim
            if (!isValid) {
                event.preventDefault(); // Menghentikan pengiriman form ke server
                alert("Mohon perbaiki kesalahan berikut sebelum menyimpan:\n\n" + errorMessage);
            }
        });
    }

    // konfirmasi hapus
    window.konfirmasiHapus = function (namaPegawai, urlHapus) {
        const tanya = confirm(`Apakah Anda yakin ingin menghapus data pegawai "${namaPegawai}"?\nData yang dihapus tidak dapat dikembalikan!`);
        if (tanya) {
            window.location.href = urlHapus;
        }
    };

    // fitur jQuery: Live Search & AJAX Request Cek NIP/Email
    if (typeof jQuery !== "undefined") {
        // 1. Live filter baris tabel
        $("#searchPegawai").on("keyup", function () {
            const keyword = $(this).val().toLowerCase();

            $("#tabelPegawai tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);
            });
        });

        // ajax cek ketersediaan di server
        function cekKetersediaanAjax(inputSelector, statusSelector, fieldName) {
            const $input = $(inputSelector);
            const $status = $(statusSelector);

            // Hanya jalankan jika elemen status ada
            if ($input.length === 0 || $status.length === 0) return;

            $input.on("blur", function () {
                const nilai = $input.val().trim();
                if (nilai.length < 3) {
                    $status.text("").removeClass("text-danger text-success");
                    $input.removeClass("is-valid").attr("data-taken", "false");
                    return;
                }

                // Kirim HTTP GET request via jQuery AJAX ke server
                $.ajax({
                    url: "../process/cek_ketersediaan.php",
                    method: "GET",
                    data: { field: fieldName, nilai: nilai },
                    dataType: "json",
                    success: function (res) {
                        if (res.tersedia) {
                            $input.removeClass("is-invalid").addClass("is-valid").attr("data-taken", "false");
                            $status.text("✓ " + res.pesan).removeClass("text-danger").addClass("text-success");
                        } else {
                            $input.removeClass("is-valid").addClass("is-invalid").attr("data-taken", "true");
                            $status.text("✗ " + res.pesan).removeClass("text-success").addClass("text-danger");
                        }
                    },
                    error: function (xhr, status, err) {
                        console.error("AJAX Error saat mengecek " + fieldName + ":", status, err);
                    }
                });
            });
        }
        cekKetersediaanAjax("#nip", "#statusNip", "nip");
        cekKetersediaanAjax("#email", "#statusEmail", "email");
    }
});