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
        $this->authorize('manage settings', SiteSetting::class);

        $keys = [
            'company_name', 'company_slogan', 'legal_notice',
            'about_history', 'about_mission', 'about_vision',
            'about_values', 'about_quality_policy',
        ];

        foreach ($keys as $key) {
            $value = SiteSetting::get($key);
            $this->settings[$key] = is_array($value) ? ($value['body'] ?? $value) : $value;
        }
    }

    public function save(): void
    {
        $this->authorize('manage settings', SiteSetting::class);

        foreach ($this->settings as $key => $value) {
            SiteSetting::put($key, in_array($key, ['company_name', 'company_slogan', 'legal_notice']) ? $value : ['body' => $value]);
        }

        $this->dispatch('notify', message: 'Configuración guardada correctamente.');
    }

    public function render(): View
    {
        return view('livewire.admin.settings.settings-form');
    }
}
