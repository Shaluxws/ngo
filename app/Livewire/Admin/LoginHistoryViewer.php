<?php

namespace App\Livewire\Admin;

use App\Models\LoginHistory;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class LoginHistoryViewer extends Component
{
    use WithPagination;

    public string $search = '';
    public string $userFilter = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingUserFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = LoginHistory::with('user')->latest('login_at');

        if (!empty($this->search)) {
            $query->where('ip_address', 'like', "%{$this->search}%");
        }

        if (!empty($this->userFilter)) {
            $query->where('user_id', $this->userFilter);
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $histories = $query->paginate(20);
        $users = User::orderBy('name')->get();

        return view('livewire.admin.login-history-viewer', [
            'histories' => $histories,
            'users' => $users,
        ]);
    }
}
