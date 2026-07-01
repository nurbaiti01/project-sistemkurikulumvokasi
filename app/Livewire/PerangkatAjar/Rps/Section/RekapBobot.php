<?php

namespace App\Livewire\PerangkatAjar\Rps\Section;

use Livewire\Component;

class RekapBobot extends Component
{

    public int $totalMenitSemester = 0;

    public int $totalJamSemester = 0;

    public $asesmen = [
        'uts' => true,
        'uas' => true,
    ];

    public $rpsSummary = [
        'blok' => 5,
        'jam_per_blok' => 6,
        'menit_per_jam' => 170,
        'asesmen' => 2,
        'jam_asesmen' => 2,
    ];

    public function mount($totalMenitSemester = 0, $totalJamSemester = 0){
        $this->totalMenitSemester = $totalMenitSemester;
        $this->totalJamSemester = $totalJamSemester;
    }

    
    public function rpsFormula()
    {
        $totalMenit = $this->totalMenitSemester;

        $blok = 5;
        $jamPerBlok = 6;
        $menitPerJam = 170;

        return [
            'blok' => $blok,
            'jam' => $jamPerBlok,
            'menit' => $menitPerJam,
            'asesmen' => 2,
            'jam_asesmen' => 2,
            'total_menit' => $totalMenit,
            'total_jam' => floor($totalMenit / 60),
        ];
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.rps.section.rekap-bobot');
    }
}
