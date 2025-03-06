<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\Rental;

class RentalStatusMail extends Mailable
{
    public $rental;
    public $statusMessage;
    public $additionalMessage;

    public function __construct(Rental $rental, $status, $message = null)
    {
        $this->rental = $rental->load(['user', 'bus']); // eager load relasi
        $this->statusMessage = $this->getStatusMessage($status);
        $this->additionalMessage = $message;
    }

    public function build()
    {
        return $this->subject("Status Penyewaan Bus")
                   ->view('emails.rental-status');
    }

    private function getStatusMessage($status)
    {
        return match($status) {
            'pending' => 'sedang menunggu konfirmasi',
            'confirmed' => 'dikonfirmasi',
            'cancelled' => 'dibatalkan',
            'completed' => 'selesai',
            'rejected' => 'ditolak',
            'paid' => 'telah dibayar lunas',
            'partial' => 'telah dibayar sebagian',
            'payment_pending' => 'menunggu verifikasi pembayaran',
            'payment_success' => 'pembayaran telah diverifikasi',
            'payment_failed' => 'pembayaran ditolak',
            default => $status
        };
    }
}