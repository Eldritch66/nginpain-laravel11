<?php

namespace App\Livewire\Properti;

use App\Models\Properti;
use App\Models\Sewa;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PropertiList extends Component
{
    use WithPagination;

    public int $perPage = 6;

    #[Computed]
    public function properti()
    {
        $query = Properti::with('foto');

        $query->orderBy('nama_properti');

        $occupiedIds = Sewa::whereIn('status_sewa', ['aktif', 'pending'])
            ->pluck('properti_id')
            ->toArray();

        $propertiList = $query->paginate($this->perPage);

        foreach ($propertiList as $p) {
            $p->isOccupied = in_array($p->id, $occupiedIds);
        }

        return $propertiList;
    }

    public function render()
    {
        return view('livewire.properti.properti-list', [
            'properti' => $this->properti,
        ]);
    }
}
