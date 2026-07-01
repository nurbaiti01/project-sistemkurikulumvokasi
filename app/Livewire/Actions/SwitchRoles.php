<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Route;
class SwitchRoles extends Component
{
    use WireUiActions;
    public function switchRole($roleId)
    {
        $previousUrl = url()->previous();
        $user = auth()->user();


        // Pastikan user memang memiliki role tersebut (keamanan)
        $role = $user->roles()->where('role_id', $roleId)->first();

        if ($role) {
            // Update session
            session(['active_role' => $role->name]);
            session(['active_role_id' => $role->id]);

            return $this->redirect(route('dashboard'), navigate: true);
            // return redirect()->route('dashboard')->with('status', 'Role berhasil diubah ke ' . $role->name);
        }
    }
    public function render()
    {
        return view('livewire.actions.switch-roles');
    }
}
