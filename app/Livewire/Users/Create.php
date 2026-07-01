<?php
namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\UserRole;
use App\Models\User;

class Create extends Component
{
    public $listRoles = [];
    public $name, $email, $password;
    public $role = []; // array untuk checkbox multiple

    public function mount()
    {
        $this->listRoles = UserRole::where('id', 1)->get();
    }

    public function store()
    {
        $validate = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required|array',
            'role.*' => 'boolean',
        ],
        [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email already exists.',
            'password.required' => 'Password is required.',
            'role.required' => 'Please select at least one role.',
        ]   
        );
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $user->roles()->sync($this->role);
        $this->dispatch('created');
    }

    public function createRoleUser()
    {

    }

    public function render()
    {
        return view('pages.users.create');
    }
}

