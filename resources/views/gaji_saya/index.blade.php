<x-layout title="Gaji Saya">
    <div class="mt-5 mb-4">
        <h3 class="">Gaji Saya</h3>
        <p class="small font-italic">Data Gaji Saya</p>
    </div>
    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Bulan</th>
                    <th>Total Gaji</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const gajiSayaRoute = "{{ route('gaji-saya.index') }}";
            const gajiSayaMessage = {!! json_encode(session('berhasil')) !!};
        </script>
        <script src="{{ asset('libs/js/gaji_saya.js') }}"></script>
        <script>
            function cekKode(idGaji, emailGuru) {
                Swal.fire({
                    title: "Kode Akses",
                    input: "text",
                    text: `Masukkan kode akses slip gaji yang sudah dikirim ke email ${emailGuru}.`,
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    showCancelButton: true,
                    confirmButtonText: "Lihat Slip",
                    showLoaderOnConfirm: true,
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    customClass: {
                        confirmButton: 'custom-swal-button',
                        cancelButton: 'custom-swal-button'
                    },
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
                        window.open(`/gaji/${result.value.id}`, '_blank');
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    }
                });
            }
        </script>
    @endpush
</x-layout>
