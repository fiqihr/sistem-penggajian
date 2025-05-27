$(document).ready(function () {
    $("#my-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: kodeAksesRoute,
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
                data: "kode_akses_expired",
                name: "kode_akses_expired",
                className: "align-middle",
            },
            {
                data: "kode_akses",
                name: "kode_akses",
                className: "align-middle",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, "All"],
        ],
        layout: {
            topEnd: "",
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
    // if (gajiSayaMessage) {
    //     gajiSayaBerhasil(gajiSayaMessage);
    // }
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

function salinKode(kode) {
    navigator.clipboard.writeText(kode).then(() => {
        Swal.fire({
            position: "center",
            icon: "info",
            text: "Kode berhasil disalin!",
            showConfirmButton: false,
            timer: 1500,
        });
    });
}

function generateKode(idGaji) {
    Swal.fire({
        title: "Generate Ulang Kode?",
        text: "Kode lama akan diganti dan kode baru akan dikirim ulang ke email anda!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        showLoaderOnConfirm: true,
        cancelButtonText: "Batal",
        confirmButtonColor: "#3B82F6",
        cancelButtonColor: "#d33",
        customClass: {
            confirmButton: "custom-swal-button",
            cancelButton: "custom-swal-button",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/kode-akses/generate/${idGaji}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            title: "Sukses!",
                            text: data.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => {
                            location.reload(); // Me-refresh seluruh halaman
                        });
                    } else {
                        Swal.fire("Gagal!", data.message, "error");
                    }
                });
        }
    });
}

function masukkanKode(idGaji, bulan) {
    Swal.fire({
        title: "Simpan Kode Akses",
        input: "text",
        text: `Masukkan kode akses slip gaji bulan ${bulan} yang sudah dikirim ke email anda untuk disimpan.`,
        inputAttributes: {
            autocapitalize: "off",
        },
        showCancelButton: true,
        confirmButtonText: "Simpan",
        showLoaderOnConfirm: true,
        cancelButtonText: "Batal",
        confirmButtonColor: "#17a2b8",
        cancelButtonColor: "#d33",
        customClass: {
            confirmButton: "custom-swal-button",
            cancelButton: "custom-swal-button",
        },
        preConfirm: async (kode) => {
            try {
                const response = await fetch(`/kode-akses/simpan`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        id: idGaji,
                        kode: kode,
                    }),
                });

                const data = await response.json();

                if (!response.ok || data.message === "Kode salah.") {
                    throw new Error(data.message || "Kode salah");
                }

                return true;
            } catch (error) {
                Swal.showValidationMessage(`Gagal: ${error.message}`);
            }
        },
        allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Sukses!",
                text: "Kode berhasil disimpan",
                icon: "success",
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                location.reload(); // Me-refresh seluruh halaman
            });
        }
    });
}
