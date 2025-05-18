<x-layout>
    <div class="mt-5 mb-4">
        <h3 class="">Cetak Slip Gaji</h3>
        <p class="small font-italic">Potongan Gaji &rsaquo; Cetak Slip Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('gaji.detail') }}" class="col-lg-6 mx-auto">
            <div class="mb-4">
                <label for="id_guru">Nama Guru</label>
                <select class="form-control" name="id_guru" id="id_guru">
                    <option selected disabled value="">-- Nama Guru --</option>
                    @foreach ($semua_guru as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="bulan" class="form-label">Bulan</label>
                <input id="bulan" type="month" name="bulan" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="jml_tunjangan">Tunjangan <span class="small font-italic">(Kosongi jika tidak ada
                        tunjangan)</span> </label>
                <input type="number" name="jml_tunjangan" id="jml_tunjangan" class="form-control">
            </div>
            {{-- <div class="mb-4">
                <label for="id_potongan_gaji">Potongan Gaji</label>
                <select class="form-control" name="id_guru" id="id_guru">
                    <option selected disabled value="">-- Jenis Potongan --</option>
                    @foreach ($semua_guru as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div> --}}

            {{-- <div id="presensi-data" class="mt-4 col-lg-6 mx-auto"></div> --}}

            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class=" btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>
                    <span class="ml-1">Simpan</span>
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            // function fetchPresensi() {
            //     const id_guru = $('#id_guru').val();
            //     const bulan = $('#bulan').val();

            //     if (id_guru && bulan) {
            //         $.ajax({
            //             url: '{{ route('presensi.get.json') }}',
            //             method: 'GET',
            //             data: {
            //                 id_guru: id_guru,
            //                 bulan: bulan
            //             },
            //             success: function(response) {
            //                 let html = '';
            //                 if (response.status === 'success') {
            //                     html = `
    //                 <h5>Data Presensi</h5>
    //                 <div class="form-group">
    //                     <label>Hadir</label>
    //                     <input type="number" class="form-control" value="${response.data.hadir}" disabled>
    //                 </div>
    //                 <div class="form-group">
    //                     <label>Sakit</label>
    //                     <input type="number" class="form-control" value="${response.data.sakit}" disabled>
    //                 </div>
    //                 <div class="form-group">
    //                     <label>Alpha</label>
    //                     <input type="number" class="form-control" value="${response.data.alpha}" disabled>
    //                 </div>
    //             `;
            //                 } else {
            //                     html = `<p class="text-danger"><strong>Data presensi tidak ditemukan.</strong></p>`;
            //                 }

            //                 $('#presensi-data').html(html);
            //             }
            //         });
            //     } else {
            //         $('#presensi-data').html('');
            //     }
            // }

            // $('#id_guru, #bulan').on('change', fetchPresensi);
        </script>
    @endpush
</x-layout>
