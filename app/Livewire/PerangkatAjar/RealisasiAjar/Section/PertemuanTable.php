<?php

namespace App\Livewire\PerangkatAjar\RealisasiAjar\Section;

use Livewire\Component;
use App\Models\RealisasiPengajaranDetail;
use Carbon\Carbon;
class PertemuanTable extends Component
{

    public $pertemuans = [];

    public $isEdit = false;
    public $isView = false;
    public $selectedId = null;
    protected $listeners = ['requestFormData'];

    public function openEdit()
    {
        $getData = RealisasiPengajaranDetail::where('realisasi_id', $this->selectedId)->get();
        if ($getData) {
            foreach ($getData as $data) {
                $this->pertemuans[] = [
                    'pertemuan_ke' => $data->pertemuan_ke,
                    'tanggal' => Carbon::parse($data->tanggal)->format('Y-m-d'),
                    'pokok_bahasan' => $data->pokok_bahasan,
                    'jam' => $data->jam,
                    'paraf' => $data->paraf,
                ];
            }
        }
        $this->addPertemuan();

    }


    public function requestFormData()
    {
        $this->validate([
            'pertemuans' => ['required', 'array', 'min:1'],
            'pertemuans.*.tanggal' => ['required', 'date'],
            'pertemuans.*.pokok_bahasan' => ['required', 'string'],
            'pertemuans.*.jam' => ['required', 'string'],
            'pertemuans.*.paraf' => ['required', 'boolean'],
        ]);

        $this->dispatch(
            'formDataReady',
            section: 'pertemuan',
            data: $this->pertemuans
        );
    }

    public function mount(?int $selectedId = null, bool $isEdit = false, bool $isView = false)
    {
        if ($selectedId && ($isEdit || $isView)) {
            $this->selectedId = $selectedId;
            $this->isEdit = $isEdit;
            $this->isView = $isView;
            $this->openEdit();
        } else {
            $this->addPertemuan();

        }

    }
    public function addPertemuan()
    {
        $this->pertemuans[] = [
            'pertemuan_ke' => count($this->pertemuans) + 1,
            'tanggal' => '',
            'pokok_bahasan' => '',
            'jam' => '',
            'paraf' => false,
        ];
    }

    public function removePertemuan($index)
    {
        if (count($this->pertemuans) <= 1) {
            return;
        }

        unset($this->pertemuans[$index]);
        $this->pertemuans = array_values($this->pertemuans);
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.realisasi-ajar.section.pertemuan-table');
    }
}
