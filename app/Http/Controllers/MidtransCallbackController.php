<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans)
    {
        try {
            $notification = $request->all();

            $orderId = $notification['order_id'] ?? null;
            $transactionStatus = $notification['transaction_status'] ?? null;
            $paymentType = $notification['payment_type'] ?? null;
            $transactionId = $notification['transaction_id'] ?? null;

            if (! $orderId) {
                return response('OK', 200);
            }

            $pembayaran = Pembayaran::where('kode_bayar', $orderId)->first();

            if (! $pembayaran) {
                Log::warning('Midtrans callback: pembayaran not found', ['order_id' => $orderId]);

                return response('OK', 200);
            }

            $newStatus = $midtrans->mapStatus($transactionStatus);

            $updateData = [
                'status' => $newStatus,
                'midtrans_transaction_id' => $transactionId,
            ];

            if ($paymentType) {
                $updateData['metode'] = $paymentType;
            }

            if ($newStatus === 'lunas') {
                $updateData['dibayar_pada'] = now();
            }

            $pembayaran->update($updateData);

            if ($newStatus === 'lunas') {
                $pembayaran->sewa->generateKodeBooking();
            }

            return response('OK', 200);
        } catch (\Throwable $e) {
            Log::error('Midtrans callback error: '.$e->getMessage());

            return response('OK', 200);
        }
    }
}
