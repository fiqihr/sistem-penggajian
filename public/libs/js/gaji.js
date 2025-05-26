$(document).ready(function () {
    $("#my-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: gajiRoute,
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
                data: "id_guru",
                name: "id_guru",
                className: "align-middle",
            },
            {
                data: "jabatan",
                name: "jabatan",
                className: "align-middle",
            },
            {
                data: "gaji_pokok",
                name: "gaji_pokok",
                className: "align-middle",
            },
            {
                data: "id_tunjangan",
                name: "id_tunjangan",
                className: "align-middle",
            },
            {
                data: "potongan",
                name: "potongan",
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
    if (gajiMessage) {
        gajiBerhasil(gajiMessage);
    }
});

function gajiBerhasil(message) {
    Swal.fire({
        position: "center",
        icon: "success",
        text: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

function deleteGaji(id) {
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

function kirimGaji(id) {
    const url = gajiKirim.replace(":id", id);
    Swal.fire({
        text: "Serahkan slip gaji?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3B82F6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ id: id }),
            })
                .then((response) => response.json())
                .then((data) => {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        text: data.message || "Slip gaji berhasil diserahkan!",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        text: "Terjadi kesalahan saat mengirim slip gaji.",
                    });
                });
        }
    });
}

let bulanTersedia = [];

$("#id_guru").on("change", function () {
    const id_guru = $(this).val();
    $("#bulan").val("").prop("disabled", true);
    $("#bulan-error").addClass("hidden");
    $("#bulan").removeClass("border-red-500");

    if (id_guru) {
        $.ajax({
            url: presensiCek,
            method: "GET",
            data: {
                id_guru,
            },
            success: function (response) {
                if (response.status === "success") {
                    bulanTersedia = response.bulans;
                    $("#bulan").prop("disabled", false);
                }
            },
        });
    }
});

$("#bulan").on("change", function () {
    const selected = $(this).val(); // format: 2025-05

    if (!bulanTersedia.includes(selected)) {
        // Tampilkan pesan error & beri warna merah
        $("#bulan-error")
            .text("Presensi Guru belum diisi pada bulan ini.")
            .show();
        $("#bulan").addClass("is-invalid"); // Bootstrap: border merah
        $("#btn-submit").prop("disabled", true);
    } else {
        // Sembunyikan pesan error & hapus warna merah
        $("#bulan-error").hide().text("");
        $("#bulan").removeClass("is-invalid");
        $("#btn-submit").prop("disabled", false);
    }
});
