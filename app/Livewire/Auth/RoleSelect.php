<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class RoleSelect extends Component
{
    public string $error = '';

    public function selectRole(string $role)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect('/login', navigate: true);
        }

        if (! in_array($role, ['penyewa', 'pemilik'])) {
            $this->error = 'Role tidak valid';

            return;
        }

        $user->role = $role;
        $user->save();

        return $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.role-select');
    }
}
