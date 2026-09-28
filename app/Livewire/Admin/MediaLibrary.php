<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class MediaLibrary extends Component
{
    use WithPagination, WithFileUploads;

    public $uploadFiles = [];
    public string $search = '';

    // Edit modal
    public bool $showEditModal = false;
    public ?int $selectedMediaId = null;
    public string $editTitle = '';
    public string $editAltText = '';

    // Selection mode (when embedded or used via modal picker)
    public bool $isPickerMode = false;
    public ?string $pickerEvent = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedUploadFiles(): void
    {
        $this->validate([
            'uploadFiles.*' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
        ]);

        foreach ($this->uploadFiles as $file) {
            try {
                \App\Services\FileSecurityService::validateAndSanitize($file, 'uploadFiles');
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->addError('uploadFiles', $e->getMessage());
                return;
            }
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType() ?: 'image/jpeg';
            $fileSize = $file->getSize();

            $path = $file->store('media', 'public');
            $fileName = basename($path);

            $width = null;
            $height = null;
            if (str_starts_with($mimeType, 'image/')) {
                $imageSize = @getimagesize($file->getRealPath());
                if ($imageSize) {
                    $width = $imageSize[0];
                    $height = $imageSize[1];
                }
            }

            $media = Media::create([
                'file_name' => $fileName,
                'original_name' => $originalName,
                'file_path' => $path,
                'disk' => 'public',
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'width' => $width,
                'height' => $height,
                'alt_text' => pathinfo($originalName, PATHINFO_FILENAME),
                'title' => pathinfo($originalName, PATHINFO_FILENAME),
                'uploaded_by' => Auth::id(),
            ]);

            AuditService::log(
                "Uploaded media '{$media->original_name}'",
                'media',
                $media->id,
                null,
                ['file_name' => $fileName, 'size' => $fileSize]
            );
        }

        $this->uploadFiles = [];
        session()->flash('success', 'Media files uploaded successfully.');
    }

    public function openEdit(int $id): void
    {
        $media = Media::findOrFail($id);
        $this->selectedMediaId = $media->id;
        $this->editTitle = $media->title ?? '';
        $this->editAltText = $media->alt_text ?? '';
        $this->showEditModal = true;
    }

    public function saveEdit(): void
    {
        if ($this->selectedMediaId) {
            $media = Media::findOrFail($this->selectedMediaId);
            $media->update([
                'title' => $this->editTitle,
                'alt_text' => $this->editAltText,
            ]);

            AuditService::log(
                "Updated media metadata for '{$media->original_name}'",
                'media',
                $media->id,
                null,
                ['title' => $this->editTitle, 'alt_text' => $this->editAltText]
            );

            session()->flash('success', 'Media metadata updated.');
            $this->showEditModal = false;
        }
    }

    public function deleteMedia(int $id): void
    {
        $media = Media::findOrFail($id);

        if (!str_starts_with($media->file_path, 'http://') && !str_starts_with($media->file_path, 'https://')) {
            Storage::disk($media->disk)->delete($media->file_path);
        }

        $mediaName = $media->original_name;
        $media->delete();

        AuditService::log(
            "Deleted media '{$mediaName}'",
            'media',
            $id,
            ['file_name' => $mediaName],
            null
        );

        session()->flash('success', "Media '{$mediaName}' deleted.");
    }

    public function selectForPicker(int $id): void
    {
        $media = Media::findOrFail($id);
        $this->dispatch('media-selected', url: $media->url, id: $media->id, alt: $media->alt_text);
    }

    public function render()
    {
        $query = Media::with('uploader')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('original_name', 'like', "%{$this->search}%")
                  ->orWhere('title', 'like', "%{$this->search}%")
                  ->orWhere('alt_text', 'like', "%{$this->search}%");
            });
        }

        $mediaList = $query->paginate(18);

        return view('livewire.admin.media-library', [
            'mediaList' => $mediaList,
        ]);
    }
}
