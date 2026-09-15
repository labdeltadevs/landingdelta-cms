<div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
    {{-- LEFT: Form --}}
    <div class="lg:col-span-3">
        <flux:heading>{{ $brand?->exists ? 'Editar división' : 'Nueva división' }}</flux:heading>
        <flux:subheading>Completa los campos para {{ $brand?->exists ? 'actualizar' : 'crear' }} la división.</flux:subheading>

        <form wire:submit.prevent="save" class="mt-6 space-y-6">
            <flux:field>
                <flux:label>Nombre de la división</flux:label>
                <flux:input wire:model="name" placeholder="Ej: Delta Care" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Descripción</flux:label>
                <flux:textarea wire:model="description" rows="3" placeholder="Descripción de la división..." />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>Logo</flux:label>
                <flux:input type="file" wire:model="logo" accept="image/*" />
                <flux:error name="logo" />
                @if ($logo && $logo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                    <img src="{{ $logo->temporaryUrl() }}" class="mt-2 h-20 object-contain" />
                @elseif ($logo && is_string($logo))
                    <img src="{{ Storage::disk('public')->url($logo) }}" class="mt-2 h-20 object-contain" />
                @elseif ($brand?->logo_path)
                    <img src="{{ $brand->logo_url }}" class="mt-2 h-20 object-contain" />
                @endif
            </flux:field>

            <flux:switch wire:model="is_active" :checked="$is_active" label="Activa" />

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">{{ $brand?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
                <flux:button :href="route('admin.brands.index')" wire:navigate variant="ghost">Cancelar</flux:button>
            </div>
        </form>
    </div>

    {{-- RIGHT: Live Preview --}}
    <div class="lg:col-span-2 self-start">
        @php
            $previewLogoUrl = null;
            if ($logo && $logo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $previewLogoUrl = $logo->temporaryUrl();
            } elseif ($logo && is_string($logo)) {
                $previewLogoUrl = Storage::disk('public')->url($logo);
            } elseif ($brand?->logo_path) {
                $previewLogoUrl = $brand->logo_url;
            }

            $previewName = $name ?: ($brand?->name ?? 'Nombre de la división');
            $previewDesc = $description ?: ($brand?->description ?? 'Descripción de la división...');
            $previewInitial = substr($previewName, 0, 1);
        @endphp

        <div class="sticky top-24">
            <div class="text-center mb-4">
                <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-zinc-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    Vista previa
                </span>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-2xl border p-8 shadow-lg transition-all duration-300
                        {{ $is_active ? 'border-[#ff671f]/20 shadow-[#ff671f]/5' : 'border-zinc-200 opacity-60' }}">

                {{-- Inactive badge --}}
                @if (!$is_active)
                    <div class="absolute -top-2 -right-2 bg-zinc-200 text-zinc-600 text-[10px] font-semibold px-2.5 py-0.5 rounded-full shadow-sm">
                        Inactiva
                    </div>
                @endif

                {{-- Logo --}}
                <div class="flex justify-center mb-6">
                    @if ($previewLogoUrl)
                        <img src="{{ $previewLogoUrl }}" alt="{{ $previewName }}"
                             class="h-24 w-24 sm:h-28 sm:w-28 object-contain transition-all duration-300" />
                    @else
                        <div class="flex h-24 w-24 sm:h-28 sm:w-28 items-center justify-center rounded-xl bg-gradient-to-br from-[#ff671f]/10 to-[#ff671f]/5 text-4xl font-bold text-[#ff671f]/70">
                            {{ $previewInitial }}
                        </div>
                    @endif
                </div>

                {{-- Name --}}
                <h3 class="text-lg font-bold text-zinc-900 text-center">{{ $previewName }}</h3>

                {{-- Description --}}
                @if ($previewDesc)
                    <p class="mt-2 text-sm text-zinc-500 text-center leading-relaxed line-clamp-3 max-w-xs mx-auto">
                        {{ $previewDesc }}
                    </p>
                @endif

                {{-- CTA --}}
                <div class="mt-6 flex justify-center">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#ff671f]">
                        Ver productos
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Hint --}}
            <p class="mt-4 text-center text-[11px] text-zinc-400 leading-relaxed">
                Así se verá la división en la sección
                <br />«Nuestras Divisiones» del sitio web.
            </p>
        </div>
    </div>
</div>
