<?php

namespace App\Services;

use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function createSnapTransaction(Pembayaran $pembayaran): object
    {
        $sewa = $pembayaran->sewa;
        $penyewa = $sewa->penyewa;

        $params = [
            'transaction_details' => [
                'order_id' => $pembayaran->kode_bayar,
                'gross_amount' => (int) $pembayaran->jumlah,
            ],
            'customer_details' => [
                'first_name' => $penyewa->name,
                'email' => $penyewa->email,
            ],
            'item_details' => [
                [
                    'id' => $sewa->properti->kode_properti,
                    'price' => (int) $sewa->total_harga,
                    'quantity' => 1,
                    'name' => 'Sewa '.$sewa->properti->nama_properti.' ('.$sewa->durasi_bulan.' bln)',
                ],
                [
                    'id' => 'BIAYA-LAYANAN',
                    'price' => (int) $sewa->biaya_layanan,
                    'quantity' => 1,
                    'name' => 'Biaya Layanan',
                ],
                [
                    'id' => 'PEMELIHARAAN',
                    'price' => (int) ceil($sewa->total_harga * 0.05),
                    'quantity' => 1,
                    'name' => 'Biaya Pemeliharaan',
                ],
            ],
            'callbacks' => [
                'finish' => url('/payment/finish?order_id='.$pembayaran->kode_bayar),
            ],
        ];

        $token = Snap::getSnapToken($params);

        return (object) [
            'token' => $token,
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/'.$token,
        ];
    }

    public function getSnapToken(array $params): string
    {
        return Snap::getSnapToken($params);
    }

    public function status(string $orderId): object
    {
        return Transaction::status($orderId);
    }

    public function updateStatus(Pembayaran $pembayaran): void
    {
        try {
            $response = $this->status($pembayaran->kode_bayar);

            $newStatus = $this->mapStatus($response->transaction_status);

            $updateData = ['status' => $newStatus];

            if (isset($response->payment_type)) {
                $updateData['metode'] = $response->payment_type;
            }

            if (isset($response->transaction_id)) {
                $updateData['midtrans_transaction_id'] = $response->transaction_id;
            }

            if ($newStatus === 'lunas' && ! $pembayaran->dibayar_pada) {
                $updateData['dibayar_pada'] = now();
            }

            $pembayaran->update($updateData);

            if ($newStatus === 'lunas') {
                $pembayaran->sewa->generateKodeBooking();
            }
        } catch (\Throwable) {
        }
    }

    public function mapStatus(string $transactionStatus): string
    {
        return match ($transactionStatus) {
            'capture', 'settlement' => 'lunas',
            'pending' => 'menunggu',
            'deny', 'cancel' => 'ditolak',
            'expire' => 'kadaluarsa',
            default => 'menunggu',
        };
    }
}
