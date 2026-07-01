<?php

namespace App\Livewire\PerangkatAjar;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
#[Title('Data Kurikulum')]
#[Layout('components.layouts.sidebar')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.perangkat-ajar.index');
    }
}
