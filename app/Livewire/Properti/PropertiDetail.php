<?php

namespace App\Livewire\Properti;

use App\Models\Properti;
use App\Models\Sewa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PropertiDetail extends Component
{
    public Properti $properti;

    public int $months = 2;

    public function mount(string $id)
    {
        $this->properti = Properti::with(['foto', 'unit', 'pemilik'])->findOrFail($id);
    }

    #[Computed]
    public function isTersedia(): bool
    {
        return ! Sewa::where('properti_id', $this->properti->id)
            ->whereIn('status_sewa', ['aktif', 'pending'])
            ->exists();
    }

    #[Computed]
    public function isPemilik(): bool
    {
        $user = Auth::user();

        return $user && $user->id === $this->properti->pemilik_id;
    }

    #[Computed]
    public function startDate(): Carbon
    {
        return now('Asia/Jakarta');
    }

    #[Computed]
    public function endDate(): Carbon
    {
        return now('Asia/Jakarta')->addMonths($this->months);
    }

    public function incrementMonths()
    {
        $this->months++;
    }

    public function decrementMonths()
    {
        $this->months = max(2, $this->months - 1);
    }

    #[Computed]
    public function extraMonths(): int
    {
        return max(0, $this->months - 2);
    }

    #[Computed]
    public function totalHarga(): float
    {
        return $this->properti->harga_per_dua_bulan + $this->extra_months * $this->properti->harga_per_bulan;
    }

    #[Computed]
    public function serviceFee(): float
    {
        return round($this->total_harga * 0.025, 2);
    }

    #[Computed]
    public function tax(): int
    {
        return (int) ceil($this->total_harga * 0.1);
    }

    #[Computed]
    public function grandTotal(): float
    {
        return $this->total_harga + $this->service_fee + $this->tax;
    }

    public function book()
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect('/login', navigate: true);
        }
        if ($user->role === 'new') {
            return $this->redirect('/role', navigate: true);
        }
        if ($user->role === 'pemilik') {
            session()->flash('error', 'Anda tidak dapat menyewa properti sebagai pemilik. Silakan gunakan akun penyewa.');

            return;
        }
        if ($this->is_pemilik) {
            session()->flash('error', 'Anda tidak dapat menyewa properti milik sendiri');

            return;
        }
        if (! $this->is_tersedia) {
            session()->flash('error', 'Properti ini sudah disewa oleh penyewa lain');

            return;
        }

        $startDate = now('Asia/Jakarta');
        $endDate = now('Asia/Jakarta')->addMonths($this->months);

        $sewa = Sewa::create([
            'penyewa_id' => $user->id,
            'properti_id' => $this->properti->id,
            'tanggal_mulai' => $startDate->format('Y-m-d'),
            'tanggal_selesai' => $endDate->format('Y-m-d'),
            'durasi_bulan' => $this->months,
            'total_harga' => $this->total_harga,
            'biaya_layanan' => $this->service_fee,
            'status_sewa' => 'pending',
        ]);

        return $this->redirect('/pembayaran/'.$sewa->id, navigate: true);
    }

    public function render()
    {
        return view('livewire.properti.properti-detail');
    }
}
