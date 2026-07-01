<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\UserRole;
use App\Models\TxUserRole;
use App\Services\UserRoleService;
class Update extends Component
{
    public $name, $email, $password;
    public $role = [];
    public $listRoles = [];
    public $isEditPassword = false;

    public $userId;

    public function mount($userId)
    {
        $user = User::find($userId);
        $this->userId = $user?->id;

        $this->name = $user?->name;
        $this->email = $user?->email;

        // Konversi role array -> boolean map
        $this->role = $user->roles->pluck('id')->toArray();
        // $this->inWd1 = TxUserRole::where('role_id', 5)->first()->role_id;

        // dump($this->inWd1);

        $this->listRoles = UserRole::all();
    }


    public function update(UserRoleService $service)
    {
        $service->updateUserWithRoles(
            $this->userId,
            $this->role,
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
            ],
            $this->isEditPassword
        );

        $this->dispatch('updated');
    }
    public function render()
    {
        return view('pages.users.update');
    }
}
