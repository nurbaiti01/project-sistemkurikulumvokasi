<?php

namespace App\Livewire\PerangkatAjar\RealisasiAjar;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\ProgramStudi;
use App\Models\RealisasiPengajaran;
use App\Models\RealisasiPengajaranDetail;
use App\Models\RealisasiPengajaranEvaluasi;
use App\Models\RealisasiPengajaranMetode;
use App\Models\RealisasiPengajaranReferensi;
use App\Models\RealisasiPengajaranApproval;
use Illuminate\Support\Facades\DB;
use WireUi\Traits\WireUiActions;

#[Layout('components.layouts.sidebar')]
class View extends Component
{
     use WireUiActions;
    public ?int $realisasiPengajaranId = null;
    public bool $isView = true;
    public function mount(int $id)
    {
        $this->realisasiPengajaranId = $id;
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.realisasi-ajar.view');
    }
}
