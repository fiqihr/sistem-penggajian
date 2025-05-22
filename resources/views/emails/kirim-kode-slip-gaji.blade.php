<h2>Halo {{ $guru->user->name }},</h2>
<p>Berikut adalah kode akses untuk membuka slip gaji anda pada bulan {{ formatBulan($bulan) }}</p>
<h1 style="color: #0b5394;">{{ $kode }}</h1>
<p>Kode ini akan kadaluarsa dalam 24 jam.</p>
<p>Terima kasih.</p>
