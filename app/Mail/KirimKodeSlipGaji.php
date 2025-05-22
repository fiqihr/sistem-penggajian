<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KirimKodeSlipGaji extends Mailable
{
    use Queueable, SerializesModels;

    public $guru;
    public $kode;
    public $bulan;

    public function __construct($guru, $kode, $bulan)
    {
        $this->guru = $guru;
        $this->kode = $kode;
        $this->bulan = $bulan;
    }

    public function build()
    {
        return $this->subject('Kode Akses Slip Gaji')
            ->view('emails.kirim-kode-slip-gaji');
    }
}
