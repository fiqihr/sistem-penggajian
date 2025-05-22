<x-layout title="Kode Akses Saya">
    <div class="mt-5 mb-4">
        <h3 class="">Kode Akses</h3>
        <p class="small font-italic">Kode Akses Saya</p>
    </div>
    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Keterangan</th>
                    <th>Expired</th>
                    <th>Kode Akses</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const kodeAksesRoute = "{{ route('kode-akses.index') }}";
            // const gajiSayaMessage = {!! json_encode(session('berhasil')) !!};
        </script>
        <script src="{{ asset('libs/js/kode_akses.js') }}"></script>
        {{-- <script>
            function cekKode(idGaji) {
                Swal.fire({
                    title: "Masukkan Kode Akses Slip Gaji",
                    input: "text",
                    inputLabel: "Kode Akses",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    showCancelButton: true,
                    confirmButtonText: "Lihat Slip",
                    showLoaderOnConfirm: true,
                    preConfirm: async (kode) => {
                        try {
                            const response = await fetch(`/gaji/cek-kode`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute("content"),
                                },
                                body: JSON.stringify({
                                    id: idGaji,
                                    kode: kode
                                }),
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                throw new Error(data.message || "Kode salah");
                            }

                            return data;
                        } catch (error) {
                            Swal.showValidationMessage(`Gagal: ${error.message}`);
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/gaji/${result.value.id}`;
                    }
                });
            }
        </script> --}}
        <script>
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
                        confirmButton: 'custom-swal-button',
                        cancelButton: 'custom-swal-button'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/kode-akses/generate/${idGaji}`, {
                                method: 'POST',
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        "content")
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: data.message,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500,
                                    }).then(() => {
                                        location.reload(); // Me-refresh seluruh halaman
                                    });
                                } else {
                                    Swal.fire('Gagal!', data.message, 'error');
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
                        autocapitalize: "off"
                    },
                    showCancelButton: true,
                    confirmButtonText: "Simpan",
                    showLoaderOnConfirm: true,
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#17a2b8",
                    cancelButtonColor: "#d33",
                    customClass: {
                        confirmButton: 'custom-swal-button',
                        cancelButton: 'custom-swal-button'
                    },
                    preConfirm: async (kode) => {
                        try {
                            const response = await fetch(`/kode-akses/simpan`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute("content"),
                                },
                                body: JSON.stringify({
                                    id: idGaji,
                                    kode: kode
                                }),
                            });

                            const data = await response.json();

                            if (!response.ok || data.message === 'Kode salah.') {
                                throw new Error(data.message || 'Kode salah');
                            }

                            return true;
                        } catch (error) {
                            Swal.showValidationMessage(`Gagal: ${error.message}`);
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Kode berhasil disimpan',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => {
                            location.reload(); // Me-refresh seluruh halaman
                        });
                    }
                });
            }
        </script>
    @endpush
</x-layout>
