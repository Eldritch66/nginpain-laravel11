<?php

namespace App\Livewire\Tiket;

use App\Models\TiketBantuan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormBantuan extends Component
{
    public string $judul = '';

    public string $kategori = '';

    public string $pesan = '';

    public ?string $error = null;

    public bool $success = false;

    protected $rules = [
        'judul' => 'required|min:3',
        'kategori' => 'required|in:teknis,pembayaran,properti,akun,lainnya',
        'pesan' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        $user = Auth::user();
        if (! $user) {
            $this->error = 'Silakan login terlebih dahulu';

            return;
        }

        TiketBantuan::create([
            'user_id' => $user->id,
            'judul' => trim($this->judul),
            'pesan' => trim($this->pesan),
            'kategori' => $this->kategori,
        ]);

        $this->success = true;
        $this->judul = '';
        $this->kategori = '';
        $this->pesan = '';
        $this->error = null;
    }

    public function render()
    {
        return view('livewire.tiket.form-bantuan');
    }
}
