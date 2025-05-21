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
                    <th>Bulan</th>
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
                    title: "Yakin ingin generate ulang kode?",
                    text: "Kode lama akan diganti dan dikirim ulang ke email!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Generate!",
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
                                    Swal.fire('Sukses!', data.message, 'success');
                                    $('#dataTable').DataTable().ajax.reload(null,
                                        false); // Reload tanpa reset halaman
                                } else {
                                    Swal.fire('Gagal!', data.message, 'error');
                                }
                            });
                    }
                });
            }
        </script>
    @endpush
</x-layout>
