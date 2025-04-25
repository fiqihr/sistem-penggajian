$(document).ready(function () {
    $("#my-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: potonganGajiRoute,
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "align-middle text-center",
            },
            {
                data: "nama_potongan",
                name: "nama_potongan",
                className: "align-middle",
            },
            {
                data: "jml_potongan",
                name: "jml_potongan",
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
    if (potonganGajiMessage) {
        potonganGajiBerhasil(potonganGajiMessage);
    }
});

function potonganGajiBerhasil(message) {
    Swal.fire({
        position: "center",
        icon: "success",
        text: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

function deleteClient(id) {
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
