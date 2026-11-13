// halaman
document.addEventListener("DOMContentLoaded", function () {

    // client side validation
    const formPegawai = document.getElementById("formPegawai");

    if (formPegawai) {
        formPegawai.addEventListener("submit", function (event) {
            let isValid = true;
            let errorMessage = "";

            // tkake elemtnt input
            const nip = document.getElementById("nip");
            const nama = document.getElementById("nama_lengkap");
            const email = document.getElementById("email");
            const noHp = document.getElementById("no_hp");
            const jabatan = document.getElementById("jabatan");
            const alamat = document.getElementById("alamat");

            // nip harus 3
            if (!nip.value.trim() || nip.value.trim().length < 3) {
                errorMessage += "- NIP wajib diisi minimal 3 karakter.\n";
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

            // format email normal
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
                errorMessage += "- Format email tidak valid (contoh: nama@domain.com).\n";
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

    //  konfirmasihapus
    window.konfirmasiHapus = function (namaPegawai, urlHapus) {
        const tanya = confirm(`Apakah Anda yakin ingin menghapus data pegawai "${namaPegawai}"?\nData yang dihapus tidak dapat dikembalikan!`);
        if (tanya) {
            window.location.href = urlHapus;
        }
    };

    // search
    if (typeof jQuery !== "undefined") {
        $("#searchPegawai").on("keyup", function () {
            const keyword = $(this).val().toLowerCase();

            $("#tabelPegawai tbody tr").filter(function () {
                // Sembunyikan baris jika teks tidak cocok dengan kata kunci
                $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);
            });
        });
    }
});