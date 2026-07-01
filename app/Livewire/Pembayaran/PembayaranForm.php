<?php

namespace App\Livewire\Pembayaran;

use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PembayaranForm extends Component
{
    #[Computed]
    public function booking(): ?array
    {
        return session('booking');
    }

    #[Computed]
    public function grandTotal(): int
    {
        return (int) ($this->booking['grand_total'] ?? 0);
    }

    public function submit()
    {
        $booking = $this->booking;

        if (! $booking) {
            session()->flash('error', 'Sesi booking tidak ditemukan. Silakan pilih properti terlebih dahulu.');

            return;
        }

        $user = Auth::user();

        $orderId = 'PAY-'.Auth::id().'-'.strtoupper(substr(uniqid(), -6));

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking['grand_total'],
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $booking['properti_kode'],
                    'price' => (int) $booking['total_harga'],
                    'quantity' => 1,
                    'name' => 'Sewa '.$booking['properti_nama'].' ('.$booking['months'].' bln)',
                ],
                [
                    'id' => 'BIAYA-LAYANAN',
                    'price' => (int) $booking['service_fee'],
                    'quantity' => 1,
                    'name' => 'Biaya Layanan',
                ],
                [
                    'id' => 'PEMELIHARAAN',
                    'price' => (int) $booking['pemeliharaan'],
                    'quantity' => 1,
                    'name' => 'Biaya Pemeliharaan',
                ],
            ],
            'callbacks' => [
                'finish' => url('/payment/finish?order_id='.$orderId),
            ],
        ];

        try {
            $midtrans = app(MidtransService::class);
            $token = $midtrans->getSnapToken($params);

            Cache::put('booking_'.$orderId, array_merge($booking, ['order_id' => $orderId, 'snap_token' => $token]), 7200);

            return $this->redirect('https://app.sandbox.midtrans.com/snap/v4/redirection/'.$token);
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal terhubung ke Midtrans: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pembayaran.pembayaran-form');
    }
}
