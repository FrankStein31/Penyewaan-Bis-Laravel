<?php

namespace App\Http\Controllers;

use App\Models\RentalExtension;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalExtensionController extends Controller
{
    public function pay(Request $request, RentalExtension $extension)
    {
        try {
            DB::beginTransaction();

            // Generate payment code
            $paymentCode = 'PAY-EXT-' . strtoupper(uniqid());

            // Upload bukti pembayaran
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            }

            // Buat record pembayaran
            $payment = Payment::create([
                'rental_id' => $extension->rental_id,
                'extension_id' => $extension->id,
                'payment_code' => $paymentCode,
                'amount' => $extension->additional_price,
                'payment_method' => 'transfer',
                'payment_proof' => $paymentProofPath,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            // Update status pembayaran extension
            $extension->update([
                'payment_status' => 'pending'
            ]);

            DB::commit();
            return back()->with('success', 'Bukti pembayaran perpanjangan berhasil dikirim dan menunggu verifikasi admin');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}