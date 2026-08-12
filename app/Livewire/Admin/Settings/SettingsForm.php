<?php

namespace App\Livewire\Admin\Settings;

use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración')]
class SettingsForm extends Component
{
    public array $settings = [];

    public function mount(): void
    {
        $this->authorize('view', new SiteSetting);

        $keys = [
            'company_name', 'company_slogan', 'legal_notice',
            'about_history', 'about_mission', 'about_vision',
            'about_values', 'about_quality_policy', 'about_milestones',
            'work_with_us_title', 'work_with_us_description', 'work_with_us_body',
            'social_linkedin', 'social_facebook', 'social_instagram', 'social_tiktok',
        ];

        foreach ($keys as $key) {
            $value = SiteSetting::get($key);
            if ($key === 'about_milestones') {
                $this->settings[$key] = is_string($value) ? $value : json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                $this->settings[$key] = is_array($value) ? ($value['body'] ?? $value) : $value;
            }
        }
    }

    public function save(): void
    {
        $this->authorize('update', new SiteSetting);

        $isSimple = [
            'company_name', 'company_slogan', 'legal_notice',
            'work_with_us_title', 'work_with_us_description',
            'social_linkedin', 'social_facebook', 'social_instagram', 'social_tiktok',
            'about_milestones',
        ];

        foreach ($this->settings as $key => $value) {
            SiteSetting::put($key, in_array($key, $isSimple) ? $value : ['body' => $value]);
        }

        $this->dispatch('notify', message: 'Configuración guardada correctamente.');
    }

    public function render(): View
    {
        return view('livewire.admin.settings.settings-form');
    }
}
