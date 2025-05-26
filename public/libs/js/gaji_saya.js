$(document).ready(function () {
    $("#my-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: gajiSayaRoute, // gajiSayaRoute sudah didefinisikan di Blade
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "align-middle text-center",
            },
            {
                data: "bulan",
                name: "bulan",
                className: "align-middle",
            },
            {
                data: "total_gaji",
                name: "total_gaji",
                className: "align-middle font-weight-bold",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                // Pastikan tombol yang memanggil cekKode(idGaji, emailGuru)
                // memiliki parameter yang benar dari data server-side
            },
        ],
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, "All"],
        ],
        layout: {
            topEnd: "search",
            bottomEnd: "paging",
            bottomStart: "info",
        },
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari data...",
            lengthMenu: "Menampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            emptyTable: "Tidak ada data",
        },
    });

    // Panggil alert jika ada message dari Laravel
    // gajiSayaMessage sudah didefinisikan di Blade
    if (gajiSayaMessage) {
        gajiSayaBerhasil(gajiSayaMessage);
    }
});

function gajiSayaBerhasil(message) {
    Swal.fire({
        position: "center",
        icon: "success",
        text: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

function deleteGajiSaya(id) {
    Swal.fire({
        text: "Apakah kamu yakin?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3B82F6",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete-form-" + id).submit();
        }
    });
}

// Fungsi cekKode dipindahkan ke sini
function cekKode(idGaji, emailGuru) {
    Swal.fire({
        title: "Kode Akses",
        input: "text",
        text: `Masukkan kode akses slip gaji yang sudah dikirim ke email ${emailGuru}.`,
        inputAttributes: {
            autocapitalize: "off",
        },
        showCancelButton: true,
        confirmButtonText: "Lihat Slip",
        showLoaderOnConfirm: true,
        cancelButtonText: "Batal",
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        customClass: {
            confirmButton: "custom-swal-button",
            cancelButton: "custom-swal-button",
        },
        preConfirm: async (kode) => {
            try {
                // Pastikan meta tag CSRF token ada di halaman HTML Anda
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");

                const response = await fetch(`/gaji/cek-kode`, {
                    // URL ini harus sesuai dengan route di Laravel Anda
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        id: idGaji,
                        kode: kode,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || "Kode salah");
                }

                if (!data.encrypted_id) {
                    throw new Error(
                        "ID terenkripsi tidak diterima dari server."
                    );
                }

                return data;
            } catch (error) {
                Swal.showValidationMessage(`Gagal: ${error.message}`);
            }
        },
        allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
        if (result.isConfirmed && result.value && result.value.success) {
            const encryptedIdToShow = result.value.encrypted_id;
            window.open(`/gaji-saya/${encryptedIdToShow}`, "_blank"); // URL ini harus sesuai dengan route di Laravel Anda
            setTimeout(() => {
                window.location.reload();
            }, 100);
        }
    });
}
