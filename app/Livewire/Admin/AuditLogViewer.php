<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AuditLogViewer extends Component
{
    use WithPagination;

    public string $search = '';
    public string $userFilter = '';
    public string $entityFilter = '';

    // Modal state for diff inspection
    public bool $showDetailModal = false;
    public ?AuditLog $selectedLog = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingUserFilter(): void
    {
        $this->resetPage();
    }

    public function updatingEntityFilter(): void
    {
        $this->resetPage();
    }

    public function inspectLog(int $id): void
    {
        $this->selectedLog = AuditLog::with('user')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function render()
    {
        $query = AuditLog::with('user')->latest('created_at');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('action', 'like', "%{$this->search}%")
                  ->orWhere('ip_address', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->userFilter)) {
            $query->where('user_id', $this->userFilter);
        }

        if (!empty($this->entityFilter)) {
            $query->where('entity_type', $this->entityFilter);
        }

        $logs = $query->paginate(20);
        $users = User::orderBy('name')->get();
        $entityTypes = AuditLog::select('entity_type')->distinct()->whereNotNull('entity_type')->pluck('entity_type');

        return view('livewire.admin.audit-log-viewer', [
            'logs' => $logs,
            'users' => $users,
            'entityTypes' => $entityTypes,
        ]);
    }
}
