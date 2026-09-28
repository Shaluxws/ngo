<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class WebsiteSettings extends Component
{
    use WithFileUploads;

    #[Url(as: 'tab')]
    public string $activeTab = 'general';

    // General Settings
    public array $settings = [];

    // SEO Settings
    public array $seo = [];

    // Top Bar & Announcement Settings
    public bool $topBarEnabled = true;
    public string $topBarBadge = '';
    public string $topBarText = '';
    public bool $topBarPhoneEnabled = true;
    public string $topBarPhone = '';
    public bool $topBarEmailEnabled = true;
    public string $topBarEmail = '';
    public string $topBarBgColor = '#166534';
    public string $topBarTextColor = '#FFFFFF';
    public string $topBarBadgeColor = '#F59E0B';
    public string $topBarLinkColor = '#FEF08A';

    // Menu Management
    public string $selectedMenuLocation = 'header';
    public string $newMenuItemLabel = '';
    public string $newMenuItemUrl = '';

    // Branding Image Uploads
    public $headerLogoFile = null;
    public $footerLogoFile = null;
    public $faviconFile = null;
    public $socialShareImageFile = null;

    // Media Picker Modal State
    public bool $showMediaPickerModal = false;
    public string $mediaPickerTarget = '';
    public string $mediaSearch = '';

    // Social Links CRUD Modal State
    public bool $showSocialModal = false;
    public ?int $editingSocialLinkId = null;
    public array $socialForm = [
        'platform' => 'Facebook',
        'url' => '',
        'icon' => 'facebook',
        'sort_order' => 1,
        'is_active' => true,
    ];

    // Social Links Delete Confirmation
    public bool $confirmingDeleteSocial = false;
    public ?int $confirmingDeleteSocialId = null;
    public string $confirmingDeleteSocialTitle = '';

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && !$user->hasRole('Super Admin') && !$user->hasAnyPermission(['website.view', 'website.edit', 'social_links.view', 'social_links.edit'])) {
            abort(403, 'Unauthorized access to Website Settings.');
        }

        $this->loadSettings();
        $this->loadSeo();
    }

    protected function checkAuthorization(string $permission = 'website.edit'): void
    {
        $user = Auth::user();
        if (!$user || (!$user->hasRole('Super Admin') && !$user->hasPermissionTo($permission))) {
            abort(403, 'Unauthorized action in Website Settings.');
        }
    }

    public function loadSettings(): void
    {
        $this->settings = [
            'site_name' => SiteSetting::get('site_name', 'NANBAN SOCIAL FOUNDATION'),
            'site_short_name' => SiteSetting::get('site_short_name', 'Nanban NGO'),
            'site_tagline' => SiteSetting::get('site_tagline', 'Serving Communities Across Tamil Nadu'),
            'state_jurisdiction' => SiteSetting::get('state_jurisdiction', 'Tamil Nadu, India'),
            'contact_phone' => SiteSetting::get('contact_phone', '+91 94420 12345'),
            'contact_email' => SiteSetting::get('contact_email', 'contact@nanbanfoundation.org.in'),
            'contact_address' => SiteSetting::get('contact_address', '42, Gandhiji Road, RS Puram, Coimbatore, Tamil Nadu - 641002'),
            'operating_hours' => SiteSetting::get('operating_hours', 'Mon - Sat: 9:00 AM - 6:00 PM'),
            'copyright_text' => SiteSetting::get('copyright_text', '© 2026 Nanban Social Foundation. All Rights Reserved.'),
            'website_status' => SiteSetting::get('website_status', 'online'),
            'timezone' => SiteSetting::get('timezone', 'Asia/Kolkata'),
            'logo_url' => SiteSetting::get('logo_url', ''),
            'favicon_url' => SiteSetting::get('favicon_url', ''),
            'footer_logo_url' => SiteSetting::get('footer_logo_url', ''),
            'social_share_image' => SiteSetting::get('social_share_image', ''),
        ];

        // Hydrate Top Bar & Announcement Settings
        $this->topBarEnabled = filter_var(SiteSetting::get('top_bar_enabled', SiteSetting::get('announcement_enabled', true)), FILTER_VALIDATE_BOOLEAN);
        $this->topBarBadge = (string) SiteSetting::get('top_bar_badge', SiteSetting::get('announcement_badge', 'Community Impact'));
        $this->topBarText = (string) SiteSetting::get('top_bar_text', SiteSetting::get('announcement_text', 'Tamil Nadu Grassroots Community Management & Social Impact'));
        $this->topBarPhoneEnabled = filter_var(SiteSetting::get('top_bar_phone_enabled', true), FILTER_VALIDATE_BOOLEAN);
        $this->topBarPhone = (string) SiteSetting::get('top_bar_phone', SiteSetting::get('contact_phone', '+91 94420 12345'));
        $this->topBarEmailEnabled = filter_var(SiteSetting::get('top_bar_email_enabled', true), FILTER_VALIDATE_BOOLEAN);
        $this->topBarEmail = (string) SiteSetting::get('top_bar_email', SiteSetting::get('contact_email', 'contact@nanbanfoundation.org.in'));
        $this->topBarBgColor = (string) SiteSetting::get('top_bar_background_color', '#166534');
        $this->topBarTextColor = (string) SiteSetting::get('top_bar_text_color', '#FFFFFF');
        $this->topBarBadgeColor = (string) SiteSetting::get('top_bar_badge_color', '#F59E0B');
        $this->topBarLinkColor = (string) SiteSetting::get('top_bar_link_color', '#FEF08A');
    }

    public function loadSeo(): void
    {
        $seo = SeoSetting::where('page_key', 'home')->first();
        if ($seo) {
            $this->seo = [
                'meta_title' => $seo->meta_title ?? '',
                'meta_description' => $seo->meta_description ?? '',
                'keywords' => $seo->keywords ?? '',
                'og_title' => $seo->og_title ?? '',
                'og_description' => $seo->og_description ?? '',
                'og_image' => $seo->og_image ?? '',
                'canonical_url' => $seo->canonical_url ?? '',
            ];
        } else {
            $this->seo = [
                'meta_title' => 'Nanban Social Foundation | Serving Communities Across Tamil Nadu',
                'meta_description' => 'Grassroots NGO working with rural and peri-urban communities across Tamil Nadu.',
                'keywords' => 'Tamil Nadu NGO, rural education, Coimbatore NGO',
                'og_title' => 'Nanban Social Foundation',
                'og_description' => 'Grassroots NGO in Tamil Nadu',
                'og_image' => '',
                'canonical_url' => 'https://nanbanfoundation.org.in',
            ];
        }
    }

    public function saveGeneral(): void
    {
        $this->checkAuthorization();

        $oldValues = [];
        foreach ($this->settings as $key => $val) {
            $oldValues[$key] = SiteSetting::get($key);
            SiteSetting::set($key, $val, 'general', 'text');
        }

        AuditService::log('Updated general website settings', 'site_settings', null, $oldValues, $this->settings);

        session()->flash('success', 'General settings updated successfully.');
    }

    /* -------------------------------------------------------------
     | Branding & Logos Image Upload & Management
     | ------------------------------------------------------------*/

    public function updatedHeaderLogoFile(): void
    {
        $this->handleBrandingUpload('headerLogoFile', 'logo_url', 'Header Brand Logo');
    }

    public function updatedFooterLogoFile(): void
    {
        $this->handleBrandingUpload('footerLogoFile', 'footer_logo_url', 'Footer Brand Logo');
    }

    public function updatedFaviconFile(): void
    {
        $this->validate([
            'faviconFile' => 'required|file|mimes:ico,png,svg,webp,jpg,jpeg|max:2048',
        ]);

        $this->storeUploadedBranding($this->faviconFile, 'favicon_url', 'Favicon Icon');
        $this->faviconFile = null;
    }

    public function updatedSocialShareImageFile(): void
    {
        $this->handleBrandingUpload('socialShareImageFile', 'social_share_image', 'Social Share Preview Image');
    }

    protected function handleBrandingUpload(string $property, string $settingKey, string $label): void
    {
        $this->validate([
            $property => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        $this->storeUploadedBranding($this->{$property}, $settingKey, $label);
        $this->{$property} = null;
    }

    protected function storeUploadedBranding($file, string $settingKey, string $label): void
    {
        $this->checkAuthorization();

        try {
            \App\Services\FileSecurityService::validateAndSanitize($file, $settingKey);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError($settingKey, $e->getMessage());
            return;
        }

        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType() ?: 'image/png';
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
            'alt_text' => $label . ' - Nanban Social Foundation',
            'title' => $originalName,
            'uploaded_by' => Auth::id(),
        ]);

        $this->settings[$settingKey] = $media->url;
        SiteSetting::set($settingKey, $media->url, 'branding', 'text');

        if ($settingKey === 'social_share_image') {
            $this->seo['og_image'] = $media->url;
            SeoSetting::updateOrCreate(
                ['page_key' => 'home'],
                ['og_image' => $media->url]
            );
        }

        AuditService::log("Uploaded new {$label} '{$originalName}'", 'site_settings', null, null, [
            $settingKey => $media->url,
            'media_id' => $media->id,
        ]);

        session()->flash('success', "{$label} uploaded and updated successfully.");
    }

    public function removeBrandingImage(string $settingKey, string $label = 'Image'): void
    {
        $this->checkAuthorization();

        $oldVal = $this->settings[$settingKey] ?? '';
        $this->settings[$settingKey] = '';
        SiteSetting::set($settingKey, '', 'branding', 'text');

        if ($settingKey === 'social_share_image') {
            $this->seo['og_image'] = '';
            SeoSetting::updateOrCreate(
                ['page_key' => 'home'],
                ['og_image' => '']
            );
        }

        AuditService::log("Removed {$label}", 'site_settings', null, [$settingKey => $oldVal], [$settingKey => '']);
        session()->flash('success', "{$label} removed successfully.");
    }

    /* -------------------------------------------------------------
     | Media Picker Modal for Branding & SEO
     | ------------------------------------------------------------*/

    public function openMediaPicker(string $targetProperty): void
    {
        $this->mediaPickerTarget = $targetProperty;
        $this->mediaSearch = '';
        $this->showMediaPickerModal = true;
    }

    public function closeMediaPicker(): void
    {
        $this->showMediaPickerModal = false;
        $this->mediaPickerTarget = '';
    }

    public function selectMediaItem(string $url): void
    {
        $this->checkAuthorization();

        if ($this->mediaPickerTarget) {
            data_set($this, $this->mediaPickerTarget, $url);

            // Persist setting immediately if it targets a branding field
            if (str_starts_with($this->mediaPickerTarget, 'settings.')) {
                $key = str_replace('settings.', '', $this->mediaPickerTarget);
                SiteSetting::set($key, $url, 'branding', 'text');
                AuditService::log("Assigned media asset to {$key}", 'site_settings', null, null, [$key => $url]);
            } elseif ($this->mediaPickerTarget === 'seo.og_image') {
                SeoSetting::updateOrCreate(
                    ['page_key' => 'home'],
                    ['og_image' => $url]
                );
                SiteSetting::set('social_share_image', $url, 'branding', 'text');
                $this->settings['social_share_image'] = $url;
                AuditService::log('Assigned media asset to OpenGraph Image', 'seo_settings', null, null, ['og_image' => $url]);
            }
        }

        $this->closeMediaPicker();
        session()->flash('success', 'Media asset selected and assigned.');
    }

    /* -------------------------------------------------------------
     | Top Bar & Announcement Actions
     | ------------------------------------------------------------*/

    public function saveTopBar(): void
    {
        $this->checkAuthorization();

        $this->validate([
            'topBarBadge' => 'nullable|string|max:50',
            'topBarText' => 'nullable|string|max:255',
            'topBarPhone' => ['nullable', 'string', 'max:30', 'regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.\/0-9]*$/'],
            'topBarEmail' => 'nullable|email|max:100',
            'topBarBgColor' => ['required', 'regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/'],
            'topBarTextColor' => ['required', 'regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/'],
            'topBarBadgeColor' => ['required', 'regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/'],
            'topBarLinkColor' => ['nullable', 'regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/'],
        ]);

        $oldValues = [
            'top_bar_enabled' => SiteSetting::get('top_bar_enabled'),
            'top_bar_badge' => SiteSetting::get('top_bar_badge'),
            'top_bar_text' => SiteSetting::get('top_bar_text'),
            'top_bar_phone_enabled' => SiteSetting::get('top_bar_phone_enabled'),
            'top_bar_phone' => SiteSetting::get('top_bar_phone'),
            'top_bar_email_enabled' => SiteSetting::get('top_bar_email_enabled'),
            'top_bar_email' => SiteSetting::get('top_bar_email'),
            'top_bar_background_color' => SiteSetting::get('top_bar_background_color'),
            'top_bar_text_color' => SiteSetting::get('top_bar_text_color'),
            'top_bar_badge_color' => SiteSetting::get('top_bar_badge_color'),
            'top_bar_link_color' => SiteSetting::get('top_bar_link_color'),
        ];

        $newValues = [
            'top_bar_enabled' => $this->topBarEnabled ? '1' : '0',
            'top_bar_badge' => trim($this->topBarBadge),
            'top_bar_text' => trim($this->topBarText),
            'top_bar_phone_enabled' => $this->topBarPhoneEnabled ? '1' : '0',
            'top_bar_phone' => trim($this->topBarPhone),
            'top_bar_email_enabled' => $this->topBarEmailEnabled ? '1' : '0',
            'top_bar_email' => trim($this->topBarEmail),
            'top_bar_background_color' => strtoupper(trim($this->topBarBgColor)),
            'top_bar_text_color' => strtoupper(trim($this->topBarTextColor)),
            'top_bar_badge_color' => strtoupper(trim($this->topBarBadgeColor)),
            'top_bar_link_color' => strtoupper(trim($this->topBarLinkColor ?: '#FEF08A')),
        ];

        foreach ($newValues as $key => $val) {
            SiteSetting::set($key, $val, 'top_bar', is_bool($val) || $val === '1' || $val === '0' ? 'boolean' : 'text');
        }

        // Keep general contact info synced if top_bar values are non-empty
        if (!empty($newValues['top_bar_phone'])) {
            SiteSetting::set('contact_phone', $newValues['top_bar_phone'], 'contact', 'text');
            $this->settings['contact_phone'] = $newValues['top_bar_phone'];
        }
        if (!empty($newValues['top_bar_email'])) {
            SiteSetting::set('contact_email', $newValues['top_bar_email'], 'contact', 'text');
            $this->settings['contact_email'] = $newValues['top_bar_email'];
        }

        AuditService::log('Updated top bar & announcement settings', 'site_settings', null, $oldValues, $newValues);

        session()->flash('success', 'Top bar settings updated successfully.');
    }

    public function resetTopBarColors(): void
    {
        $this->checkAuthorization();

        $this->topBarBgColor = '#166534';
        $this->topBarTextColor = '#FFFFFF';
        $this->topBarBadgeColor = '#F59E0B';
        $this->topBarLinkColor = '#FEF08A';

        SiteSetting::set('top_bar_background_color', '#166534', 'top_bar', 'color');
        SiteSetting::set('top_bar_text_color', '#FFFFFF', 'top_bar', 'color');
        SiteSetting::set('top_bar_badge_color', '#F59E0B', 'top_bar', 'color');
        SiteSetting::set('top_bar_link_color', '#FEF08A', 'top_bar', 'color');

        AuditService::log('Reset top bar appearance colors to default', 'site_settings', null, null, [
            'top_bar_background_color' => '#166534',
            'top_bar_text_color' => '#FFFFFF',
            'top_bar_badge_color' => '#F59E0B',
            'top_bar_link_color' => '#FEF08A',
        ]);

        session()->flash('success', 'Top bar appearance colors reset to default website appearance.');
    }

    public function saveSeo(): void
    {
        $this->checkAuthorization();

        $seo = SeoSetting::updateOrCreate(
            ['page_key' => 'home'],
            $this->seo
        );

        AuditService::log('Updated SEO settings', 'seo_settings', $seo->id, null, $this->seo);

        session()->flash('success', 'SEO meta settings saved.');
    }

    public function addMenuItem(): void
    {
        $this->checkAuthorization();

        $this->validate([
            'newMenuItemLabel' => 'required|string|max:50',
            'newMenuItemUrl' => 'required|string|max:255',
        ]);

        $menu = Menu::firstOrCreate(
            ['location' => $this->selectedMenuLocation],
            ['name' => ucfirst(str_replace('_', ' ', $this->selectedMenuLocation))]
        );

        $maxSort = MenuItem::where('menu_id', $menu->id)->max('sort_order') ?? 0;

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'label' => $this->newMenuItemLabel,
            'url' => $this->newMenuItemUrl,
            'sort_order' => $maxSort + 1,
            'is_enabled' => true,
        ]);

        AuditService::log("Added menu item '{$item->label}' to {$this->selectedMenuLocation}", 'menu_items', $item->id);

        $this->newMenuItemLabel = '';
        $this->newMenuItemUrl = '';
        session()->flash('success', 'Menu item added.');
    }

    public function toggleMenuItem(int $id): void
    {
        $this->checkAuthorization();

        $item = MenuItem::findOrFail($id);
        $item->is_enabled = !$item->is_enabled;
        $item->save();
        session()->flash('success', 'Menu item visibility toggled.');
    }

    public function deleteMenuItem(int $id): void
    {
        $this->checkAuthorization();

        $item = MenuItem::findOrFail($id);
        $item->delete();
        session()->flash('success', 'Menu item deleted.');
    }

    /* -------------------------------------------------------------
     | Social Media Links Full CRUD Operations
     | ------------------------------------------------------------*/

    public function openSocialModal(?int $id = null): void
    {
        $this->resetValidation();

        if ($id) {
            $link = SocialLink::findOrFail($id);
            $this->editingSocialLinkId = $link->id;
            $this->socialForm = [
                'platform' => $link->platform,
                'url' => $link->url,
                'icon' => $link->icon ?: 'globe',
                'sort_order' => (int) $link->sort_order,
                'is_active' => (bool) $link->is_active,
            ];
        } else {
            $maxSort = SocialLink::max('sort_order') ?? 0;
            $this->editingSocialLinkId = null;
            $this->socialForm = [
                'platform' => 'Facebook',
                'url' => '',
                'icon' => 'facebook',
                'sort_order' => $maxSort + 1,
                'is_active' => true,
            ];
        }

        $this->showSocialModal = true;
    }

    public function closeSocialModal(): void
    {
        $this->showSocialModal = false;
        $this->editingSocialLinkId = null;
    }

    public function updatedSocialFormPlatform($val): void
    {
        $platformLower = strtolower($val);
        $this->socialForm['icon'] = match(true) {
            str_contains($platformLower, 'facebook') => 'facebook',
            str_contains($platformLower, 'instagram') => 'instagram',
            str_contains($platformLower, 'youtube') => 'youtube',
            str_contains($platformLower, 'linkedin') => 'linkedin',
            str_contains($platformLower, 'twitter') || str_contains($platformLower, 'x') => 'twitter',
            str_contains($platformLower, 'whatsapp') => 'phone',
            default => 'globe',
        };
    }

    public function saveSocialLink(): void
    {
        $this->checkAuthorization('social_links.edit');

        $this->validate([
            'socialForm.platform' => 'required|string|max:50',
            'socialForm.url' => 'required|url|max:255',
            'socialForm.icon' => 'nullable|string|max:50',
            'socialForm.sort_order' => 'required|integer|min:0',
            'socialForm.is_active' => 'boolean',
        ]);

        if ($this->editingSocialLinkId) {
            $link = SocialLink::findOrFail($this->editingSocialLinkId);
            $oldValues = $link->toArray();
            $link->update([
                'platform' => trim($this->socialForm['platform']),
                'url' => trim($this->socialForm['url']),
                'icon' => strtolower($this->socialForm['icon'] ?: 'globe'),
                'sort_order' => (int) $this->socialForm['sort_order'],
                'is_active' => (bool) $this->socialForm['is_active'],
            ]);

            AuditService::log("Updated social media link '{$link->platform}'", 'social_links', $link->id, $oldValues, $link->toArray());
            session()->flash('success', "Social link '{$link->platform}' updated successfully.");
        } else {
            $link = SocialLink::create([
                'platform' => trim($this->socialForm['platform']),
                'url' => trim($this->socialForm['url']),
                'icon' => strtolower($this->socialForm['icon'] ?: 'globe'),
                'sort_order' => (int) $this->socialForm['sort_order'],
                'is_active' => (bool) $this->socialForm['is_active'],
            ]);

            AuditService::log("Created new social media link '{$link->platform}'", 'social_links', $link->id, null, $link->toArray());
            session()->flash('success', "Social link '{$link->platform}' added successfully.");
        }

        $this->closeSocialModal();
    }

    public function toggleSocialLink(int $id): void
    {
        $this->checkAuthorization('social_links.edit');

        $link = SocialLink::findOrFail($id);
        $link->is_active = !$link->is_active;
        $link->save();

        AuditService::log("Toggled status of social link '{$link->platform}' to " . ($link->is_active ? 'Active' : 'Inactive'), 'social_links', $link->id);
        session()->flash('success', "Social link '{$link->platform}' is now " . ($link->is_active ? 'active' : 'inactive') . '.');
    }

    public function moveSocialLink(int $id, string $direction): void
    {
        $this->checkAuthorization('social_links.edit');

        $links = SocialLink::orderBy('sort_order')->orderBy('id')->get();
        $currentIndex = $links->search(fn($item) => $item->id === $id);

        if ($currentIndex === false) {
            return;
        }

        if ($direction === 'up' && $currentIndex > 0) {
            $prev = $links[$currentIndex - 1];
            $current = $links[$currentIndex];

            $prevOrder = $prev->sort_order;
            $currentOrder = $current->sort_order;

            if ($prevOrder == $currentOrder) {
                $prev->sort_order = $prevOrder + 1;
                $current->sort_order = max(0, $currentOrder - 1);
            } else {
                $current->sort_order = $prevOrder;
                $prev->sort_order = $currentOrder;
            }

            $current->save();
            $prev->save();
        } elseif ($direction === 'down' && $currentIndex < $links->count() - 1) {
            $next = $links[$currentIndex + 1];
            $current = $links[$currentIndex];

            $nextOrder = $next->sort_order;
            $currentOrder = $current->sort_order;

            if ($nextOrder == $currentOrder) {
                $next->sort_order = max(0, $nextOrder - 1);
                $current->sort_order = $currentOrder + 1;
            } else {
                $current->sort_order = $nextOrder;
                $next->sort_order = $currentOrder;
            }

            $current->save();
            $next->save();
        }

        session()->flash('success', 'Social media link order updated.');
    }

    public function confirmDeleteSocial(int $id, string $title): void
    {
        $this->confirmingDeleteSocialId = $id;
        $this->confirmingDeleteSocialTitle = $title;
        $this->confirmingDeleteSocial = true;
    }

    public function cancelDeleteSocial(): void
    {
        $this->confirmingDeleteSocial = false;
        $this->confirmingDeleteSocialId = null;
        $this->confirmingDeleteSocialTitle = '';
    }

    public function performDeleteSocial(): void
    {
        $this->checkAuthorization('social_links.delete');

        if (!$this->confirmingDeleteSocialId) {
            $this->cancelDeleteSocial();
            return;
        }

        $link = SocialLink::findOrFail($this->confirmingDeleteSocialId);
        $title = $link->platform;
        $link->delete();

        AuditService::log("Deleted social media link '{$title}'", 'social_links', $this->confirmingDeleteSocialId, ['platform' => $title]);
        $this->cancelDeleteSocial();

        session()->flash('success', "Social link '{$title}' removed successfully.");
    }

    public function render()
    {
        $currentMenu = Menu::where('location', $this->selectedMenuLocation)->with('items')->first();
        $socialLinks = SocialLink::orderBy('sort_order')->get();

        $mediaList = collect();
        if ($this->showMediaPickerModal) {
            $mediaQuery = Media::latest();
            if (!empty($this->mediaSearch)) {
                $mediaQuery->where(function ($q) {
                    $q->where('original_name', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('title', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('alt_text', 'like', "%{$this->mediaSearch}%");
                });
            }
            $mediaList = $mediaQuery->take(24)->get();
        }

        return view('livewire.admin.website-settings', [
            'currentMenu' => $currentMenu,
            'socialLinks' => $socialLinks,
            'mediaList' => $mediaList,
        ]);
    }
}
