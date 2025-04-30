$(document).ready(function () {
    $("#my-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: guruRoute,
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "align-middle text-center",
            },
            {
                data: "name",
                name: "name",
                className: "align-middle",
            },
            {
                data: "nig",
                name: "nig",
                className: "align-middle",
            },
            {
                data: "jenis_kelamin",
                name: "jenis_kelamin",
                className: "align-middle",
            },
            {
                data: "nama_jabatan",
                name: "nama_jabatan",
                className: "align-middle",
            },
            {
                data: "tanggal_masuk",
                name: "tanggal_masuk",
                className: "align-middle",
            },
            {
                data: "status",
                name: "status",
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

    // Panggil alert jika ada message dari Laravel
    if (guruMessage) {
        guruBerhasil(guruMessage);
    }
});

function guruBerhasil(message) {
    Swal.fire({
        position: "center",
        icon: "success",
        text: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

function deleteGuru(id) {
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
