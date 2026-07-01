<?php

namespace App\Livewire\PerangkatAjar\Rps\Section;

use Livewire\Component;

class LearningMethodExp extends Component
{
    public array $form = [
        'metode_pembelajaran' => '',
        'pengalaman_belajar_mahasiswa' => '',
    ];
    public $referensi = [
        'utama' => [''],
        'pendukung' => [''],
    ];

    protected $listeners = ['requestFormData'];

    public function requestFormData(): void
    {
        $this->dispatch(
            'formDataReady',
            section: 'learning_method_exp',
            data: [
                'form' => $this->form,
                'referensi' => $this->referensi
            ]
        );
    }
    public function addReferensiUtama(): void
    {
        $this->referensi['utama'][] = '';
    }

    public function addReferensiPendukung(): void
    {
        $this->referensi['pendukung'][] = '';
    }

    public function removeReferensiUtama(int $index): void
    {
        if (count($this->referensi['utama']) <= 1) {
            return;
        }

        unset($this->referensi['utama'][$index]);
        $this->referensi['utama'] = array_values($this->referensi['utama']);
    }

    public function removeReferensiPendukung(int $index): void
    {
        if (count($this->referensi['pendukung']) <= 1) {
            return;
        }

        unset($this->referensi['pendukung'][$index]);
        $this->referensi['pendukung'] = array_values($this->referensi['pendukung']);
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.rps.section.learning-method-exp');
    }
}
