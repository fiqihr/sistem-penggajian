$(document).ready(function () {
    const table = $("#my-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: presensiRoute,
            data: function (d) {
                d.bulan = $("#filter-bulan").val();
                d.tahun = $("#filter-tahun").val();
            },
        },
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
                data: "nama_guru",
                name: "nama_guru",
                className: "align-middle",
            },
            {
                data: "hadir",
                name: "hadir",
                className: "align-middle",
            },
            {
                data: "sakit",
                name: "sakit",
                className: "align-middle",
            },
            {
                data: "tidak_hadir",
                name: "tidak_hadir",
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

    // Filter saat select berubah
    $("#filter-bulan, #filter-tahun").on("change", function () {
        table.ajax.reload();
    });

    if (presensiMessage) {
        presensiBerhasil(presensiMessage);
    }
});

function presensiBerhasil(message) {
    Swal.fire({
        position: "center",
        icon: "success",
        text: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

function deletePresensi(id) {
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
