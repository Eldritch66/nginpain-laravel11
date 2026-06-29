<?php

namespace App\Livewire\Pembayaran;

use App\Models\Sewa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PembayaranForm extends Component
{
    public Sewa $sewa;

    public string $metode = '';

    public function mount(string $sewaId)
    {
        $user = Auth::user();
        $this->sewa = Sewa::with('properti')
            ->where('penyewa_id', $user->id)
            ->findOrFail($sewaId);
    }

    #[Computed]
    public function serviceFee(): int
    {
        return 25000;
    }

    #[Computed]
    public function pemeliharaan(): int
    {
        return (int) ceil($this->sewa->total_harga * 0.05);
    }

    #[Computed]
    public function grandTotal(): int
    {
        return $this->sewa->total_harga + $this->service_fee + $this->pemeliharaan;
    }

    public function submit()
    {
        $this->validate(['metode' => 'required|in:QRIS,Transfer BCA,PayPal']);

        $user = Auth::user();
        if ($this->sewa->penyewa_id !== $user->id) {
            session()->flash('error', 'Sewa tidak ditemukan');

            return;
        }

        DB::transaction(function () {
            $this->sewa->pembayaran()->create([
                'metode' => $this->metode,
                'jumlah' => $this->grand_total,
                'status' => 'lunas',
            ]);

            $this->sewa->generateKodeBooking();
        });

        return $this->redirect('/account/struk/'.$this->sewa->id);
    }

    public function render()
    {
        return view('livewire.pembayaran.pembayaran-form');
    }
}
