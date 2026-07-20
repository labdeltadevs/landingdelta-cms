# 🗺️ Plan Maestro — LandingDelta CMS

> **Proyecto:** Landing page corporativa de Laboratorios Delta S.A.
> **Stack:** Laravel 13 + Livewire 4 + Flux UI 2 + Tailwind CSS 4 + Alpine.js + AOS
> **Fecha del plan:** Julio 2026

---

## Índice

1. [Home Page](#-área-1--home-page-)
2. [About / Storytime](#-área-2--about--storytime-nosotros)
3. [Job Openings / Ofertas Laborales](#-área-3--job-openings--ofertas-laborales)
4. [Navbar Rework](#-área-4--navbar-rework)
5. [Social Links / Settings](#-área-5--social-links--settings)
6. [File Upload Fixes](#-área-6--file-upload-fixes)
7. [Bug Fixes Técnicos](#-área-7--bug-fixes-técnicos)
8. [Marcas Page](#-área-8--marcas-page-marcas)
9. [Certificación Flotante](#-área-9--certificación-flotante)
10. [Roadmap visual del proyecto](#-roadmap-visual-del-proyecto)
11. [Orden de implementación](#-orden-de-implementación-para-repetir-desde-cero)

---

## 📦 ÁREA 1 — Home Page (`/`)

### Archivo
`resources/views/public/home.blade.php`

### 1.1 Hero Principal (Slider + Contenido)

| Aspecto | Implementación |
|---|---|
| **Slider de slides** | Sección con Alpine.js `x-data="{ current: 0, total: N }"`, cambia cada 6s con `x-transition:enter="transition-opacity duration-700"`. Muestra imagen, título, subtítulo y CTA desde BD (`HeroSlide`) |
| **Badge "Hecho en Bolivia"** | Imagen desde `Storage::disk('public')`, posición `fixed top-20 right-2`, `z-40`, visible en `sm:`, AOS flip-down con 800ms delay, hover scale con glow |
| **Imagen de fondo** | `fondo-main.jpg` con `blur(8px) brightness(0.45) scale(1.1)` + overlay oscuro `from-black/40 via-black/30 to-black/50` + transición blanca inferior + dot pattern corporativo |
| **Galardones** | 3 elementos circulares con iconos SVG: "30+ años de experiencia", "Orgullosamente bolivianos", "Presencia en el eje troncal". Efecto glass, animación pulse |

### 1.2 Orden de secciones en home

```
1. Slider Hero (HeroSlide de BD)
2. Categorías (grid responsivo sm:2 lg:3 xl:5)
3. Productos destacados (is_featured, grid sm:2 lg:3)
4. Marcas (grid sm:2 lg:4)
5. Oficinas / Sucursales (grid sm:2 lg:4, fondo oscuro)
```

### 1.3 Bolivia Flag Ribbon

- Efecto cinta diagonal en esquina superior derecha (`absolute -top-7 -right-7`)
- Tricolor boliviano a lo largo de la cinta: `linear-gradient(90deg, #D52B1E 0%, #D52B1E 33%, #FED000 33%, #FED000 66%, #007A33 66%, #007A33 100%)`
- Animación `waveRibbon` (4s ease-in-out infinite): variaciones sutiles de `scaleX`(0.98→1.02) y `skewX`(-1.5°→1.5°) para efecto flameado
- `will-change: transform` para rendimiento
- Base `transform: translateX(30%) rotate(45deg)` + animación en la misma clase CSS (sin conflictos de cascada)
- `transform-origin: top left`

### 1.4 AOS Animations usadas

| Efecto | Uso |
|---|---|
| `fade-up` | Secciones, cards |
| `fade-right` / `fade-left` | Elementos laterales |
| `flip-up` / `flip-down` | Badges, logos |
| `zoom-in` / `zoom-in-up` | Galardones, destacados |
| Delays escalonados | 100ms, 200ms, 300ms... |

---

## 📦 ÁREA 2 — About / Storytime (`/nosotros`)

### Archivo
`resources/views/public/about.blade.php`

### Diseño: Antes vs Ahora

| Antes | Ahora |
|---|---|
| Hero con glass card pesada, logo grande centrado, scroll indicator | Hero `min-h-[60vh]` con dot pattern overlay, heading impactante "Historias que **transforman** vidas" (naranja), quote, 2 CTAs (ancla + productos) |
| Timeline con cards grandes (`p-6/p-8`), dots circulares pequeños | Timeline más compacto (`p-5/p-6`, `mb-12/mb-16`), dots grandes (`h-10 w-10 rounded-2xl`) con iconos SVG |
| Sin barra de acento en cards | Barra degradada naranja en el top de cada card |
| Animaciones con delays 80ms | Delays más ágiles: 60ms |
| Iconos resueltos con múltiples `@if` anidados | Iconos resueltos con `match()` en bloque `@php` |
| `transition` genérico | `transition-all duration-[400ms]` |

### Fuente de datos
Los contenidos se obtienen desde `SiteSetting::get()`:
- `about_history` (title + body)
- `about_mission` (title + body)
- `about_vision` (title + body)
- `about_values` (title + body + quote)
- `about_quality_policy` (title + body)

---

## 📦 ÁREA 3 — Job Openings / Ofertas Laborales

### Creación completa desde cero (9 archivos nuevos + 3 modificados)

### 3.1 Base de datos

**Migración:** `database/migrations/2026_07_17_180001_create_job_openings_table.php`

```php
Schema::create('job_openings', function (Blueprint $table): void {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->date('valid_from');
    $table->date('valid_until');
    $table->string('image_path')->nullable();
    $table->boolean('is_active')->default(true);
    $table->unsignedInteger('sort')->default(0);
    $table->timestamps();
});
```

**Factory:** `database/factories/JobOpeningFactory.php`
- Estados: `inactive()` (is_active = false), `expired()` (valid_until = ayer)

**Seeder:** `database/seeders/JobOpeningSeeder.php`
- 3 ofertas ejemplo: Farmacéutico (SCZ), Visitador Médico (LP), Asistente Administrativo (CBA)

### 3.2 Modelo

`app/Models/JobOpening.php`

```php
#[Fillable(['title', 'description', 'valid_from', 'valid_until', 'image_path', 'is_active', 'sort'])]
class JobOpening extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active'   => 'boolean',
        'valid_from'  => 'date',
        'valid_until' => 'date',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder     // where is_active = true
    public function scopePublished(Builder $query): Builder   // active + fechas vigentes
    public function scopeOrdered(Builder $query): Builder     // orderBy sort, created_at desc

    // Accessors
    public function getImageUrlAttribute(): string            // Storage::disk('public')->url()

    // Methods
    public function isValidAttribute(): bool                 // activo + dentro de fechas
}
```

### 3.3 Política

`app/Policies/JobOpeningPolicy.php`

| Método | Permiso |
|---|---|
| `viewAny` / `view` | `view content` |
| `create` / `update` / `delete` | `manage news` |

### 3.4 Livewire Admin

#### `JobOpeningIndex` (listado)
- Search con `wire:model.live.debounce.300ms` + `updatingSearch()` resetea paginación
- Paginación: 15 items
- Flux table con columnas: Título, Vigencia (rango fechas), Activo (badge verde/gris), Acciones (dropdown: editar, eliminar)
- Empty state con `flux:callout`

#### `JobOpeningForm` (crear/editar)
- Campos: título (text), descripción (textarea), fecha desde/hasta (date), imagen (file), activo (switch)
- **Upload vía `_finishUpload` override**: guarda en `storage/app/public/job-openings/`
- Validación: `required|string|max:255` (título), `required|string` (descripción), `required|date` (fechas), `after_or_equal:valid_from`
- Preview de imagen existente o recién subida

### 3.5 Vista Pública

`resources/views/public/work-with-us.blade.php`
- Hero con título y descripción desde `SiteSetting`
- Cards de ofertas vigentes con: imagen (opcional), título, descripción truncada, fechas, y link para postularse

### 3.6 Rutas Admin

```php
// En routes/web.php, dentro del grupo admin:
Route::get('/job-openings', JobOpeningIndex::class)->name('admin.job-openings.index');
Route::get('/job-openings/create', JobOpeningForm::class)->name('admin.job-openings.create');
Route::get('/job-openings/{jobOpening}/edit', JobOpeningForm::class)->name('admin.job-openings.edit');
```

### 3.7 Sidebar

En `resources/views/layouts/app/sidebar.blade.php`:
```
Grupo "Contenido":
  → Rotafolios
  → Noticias
  → Ofertas Laborales (icono: briefcase) ★ NUEVO
```

---

## 📦 ÁREA 4 — Navbar Rework

### Archivo
`resources/views/layouts/public.blade.php`

### Cambios

| Antes | Ahora |
|---|---|
| 8 pestañas incluyendo "Inicio" | **7 pestañas** — se eliminó "Inicio" (el logo ya enlaza al home) |
| Gap `gap-8` entre items | Gap `gap-6` |
| Fuera del nav: `flux:input` de 384px | **Dentro del nav**: botón icono compacto `h-9 w-9` con dropdown |

### Items del navbar (orden actual)

1. ~~Inicio~~ (ELIMINADO — redundante)
2. Productos
3. Marcas
4. Nosotros
5. Rotafolios
6. Noticias
7. Contacto
8. Ofertas Laborales (AGREGADO)

### Search con Alpine.js

```blade
x-data="{ open: false }" @click.away="open = false"
```

- Botón icono lupa → toggle dropdown absoluto
- Input con `wire:model.live.debounce.300ms`
- Resultados con imagen, nombre, ingrediente activo, precio
- Estados: hint inicial, typing, sin resultados, resultados
- Cierra con click-away y tecla Escape

### Active route highlighting

```blade
class="{{ request()->routeIs('public.products.index') ? 'text-orange-600' : 'hover:text-zinc-900' }}"
```

---

## 📦 ÁREA 5 — Social Links / Settings

### Archivos modificados

| Archivo | Cambio |
|---|---|
| `app/Livewire/Admin/Settings/SettingsForm.php` | Keys agregadas al mount + `$isSimple` en save |
| `resources/views/livewire/admin/settings/settings-form.blade.php` | Nueva sección "Redes Sociales" con 4 inputs URL |
| `resources/views/layouts/public.blade.php` | Footer con iconos dinámicos según configuración |

### Keys de redes sociales

| Key | Almacenamiento | Ejemplo |
|---|---|---|
| `social_linkedin` | String simple | `https://linkedin.com/company/delta` |
| `social_facebook` | String simple | `https://facebook.com/delta` |
| `social_instagram` | String simple | `https://instagram.com/delta` |
| `social_tiktok` | String simple | `https://tiktok.com/@delta` |

### Footer público

```blade
@php
    $socialActive = false;
    $socialIcons = ['linkedin' => '...SVG...', 'facebook' => '...', 'instagram' => '...', 'tiktok' => '...'];
@endphp
@foreach ($socialIcons as $key => $svg)
    @php $url = \App\Models\SiteSetting::get('social_'.$key); @endphp
    @if ($url)
        @php $socialActive = true; @endphp
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" ...>
            {!! $svg !!}
        </a>
    @endif
@endforeach
@if (!$socialActive)
    <p>Síguenos para estar al día.</p>
@endif
```

- Hover effects: colores específicos por red + `scale-110`
- `target="_blank"` + `rel="noopener noreferrer"` por seguridad

---

## 📦 ÁREA 6 — File Upload Fixes

### Problema original

Livewire `WithFileUploads` intenta leer archivos temporales del disco `local`, pero al configurar `FILESYSTEM_DISK=public` en `.env`, los paths temporales apuntan al disco incorrecto.

**Error:**
```
stream_get_meta_data(): Argument #1 ($stream) must be of type resource, false given
```

### Solución: Override de `_finishUpload`

El patrón se aplicó en: `BrandForm`, `JobOpeningForm`, `BrochureForm`, `ProductForm`, `HeroSlideForm`

```php
public function _finishUpload($name, $tmpPath, $isMultiple, $append = true): void
{
    // 1. Limpiar uploads temporales viejos
    if (FileUploadConfiguration::shouldCleanupOldUploads()) {
        $this->cleanupOldUploads();
    }

    // 2. Extraer path real del archivo firmado
    $paths = collect($tmpPath)->map(fn ($signed) =>
        TemporaryUploadedFile::extractPathFromSignedPath($signed)
    )->toArray();

    $filename = $paths[0];

    // 3. Verificar que existe en disco temporal
    $disk = FileUploadConfiguration::disk();
    $storagePath = FileUploadConfiguration::path($filename, false);
    if (! Storage::disk($disk)->exists($storagePath)) {
        $this->dispatch('upload:errored', name: $name)->self();
        return;
    }

    // 4. Copiar a destino permanente con UUID
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $newFilename = Str::uuid().'.'.$extension;
    $newPath = '{directorio}/'.$newFilename;
    Storage::disk('public')->put($newPath, Storage::disk($disk)->get($storagePath));

    // 5. Limpiar temporal
    Storage::disk($disk)->delete($storagePath);
    Storage::disk($disk)->delete($storagePath.'.json');

    // 6. Asignar propiedad y notificar
    $this->{$name} = $newPath;
    $this->dispatch('upload:finished', name: $name, tmpFilenames: [$filename])->self();
    app('livewire')->updateProperty($this, $name, $newPath);
}
```

### FileUploader Service

`app/Services/FileUploader.php`

```php
class FileUploader
{
    public function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.$extension;
        return $file->storeAs($directory, $filename, $disk);
    }

    public function delete(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
```

**Nota:** `FileUploader` se usa para subidas tradicionales (formularios sin Livewire). Los formularios Livewire usan el override de `_finishUpload`.

---

## 📦 ÁREA 7 — Bug Fixes Técnicos

| # | Bug | Causa | Fix | Archivos afectados |
|---|---|---|---|---|
| 1 | **Multiple root elements** | Componentes Livewire con múltiples elementos HTML raíz | Envolver todo en un solo `<div>` | `admin.products.product-index` |
| 2 | **Attempt to read property "exists" on null** | `$brand->exists` sin nullsafe cuando el modelo es null | Cambiar a `$this->brand?->exists` | `HeroSlideForm`, `BrandForm`, `CategoryForm`, `NewsForm`, `BranchForm` |
| 3 | **ArgumentCountError en Policy** | `$this->authorize('update', Brand::class)` pasaba 1 argumento | `$this->authorize($this->brand?->exists ? 'update' : 'create', $this->brand ?? Brand::class)` | `BrandForm`, `CategoryForm`, `NewsForm`, `BranchForm`, `HeroSlideForm` |
| 4 | **TypeError en `/trabaja-con-nosotros`** | `work_with_us_title` y `work_with_us_description` se guardaban como arrays `['body' => value]` | Agregar a `$isSimple` en SettingsForm + fallback `is_array()` en vista | `SettingsForm`, `work-with-us.blade.php` |
| 5 | **"The logo failed to upload"** | Conflicto disco `local` vs `public` en Livewire upload | Override `_finishUpload` en cada Form component | `BrandForm`, `ProductForm`, `HeroSlideForm`, `BrochureForm` |
| 6 | **403 Forbidden en hero slides** | Ruta no tenía middleware `role:admin\|editor\|visor` | Agregado al grupo de rutas protegidas | `routes/web.php` |

---

## 📦 ÁREA 8 — Marcas Page (`/marcas`)

### Archivo
`resources/views/public/brands/index.blade.php`

### Cambio

| Antes | Ahora |
|---|---|
| Logos en escala de grises siempre: `grayscale transition group-hover:grayscale-0` | Logos a color siempre, hover con `scale-110` |
| Sin efecto hover visual (solo quitar grises) | `transition-all duration-300 group-hover:scale-110` |

---

## 📦 ÁREA 9 — Certificación Flotante

### Archivo
`resources/views/layouts/public.blade.php`

### Badge "Hecho en Bolivia"

```blade
<div class="group fixed top-20 right-2 z-40 hidden sm:block" data-aos="flip-down" data-aos-delay="800">
    <img src="{{ Storage::disk('public')->url('hecho_en_bolivia.png') }}"
         alt="Hecho en Bolivia"
         class="h-16 w-16 rounded-full object-cover ring-2 ring-white/50 shadow-lg
                transition-all duration-300 group-hover:scale-110
                group-hover:ring-[#ff671f] group-hover:drop-shadow-lg
                group-hover:shadow-[#ff671f]/20 cursor-pointer" />
</div>
```

| Propiedad | Valor |
|---|---|
| Position | `fixed top-20 right-2` |
| Z-index | `z-40` (debajo del header que es z-50) |
| Mobile | `hidden sm:block` |
| AOS | `flip-down` con 800ms delay |
| Hover | `scale-110`, ring naranja, glow |
| Tamaño | `h-16 w-16` (64px) |

---

## 🗺️ Roadmap visual del proyecto

```
storage/app/public/
├── logo_delta.png              ← Logo corporativo
├── matraz_naranja_no_bg.png    ← Icono/Favicon
├── fondo-main.jpg              ← Background hero principal
├── hecho_en_bolivia.png        ← Certificación flotante
├── brands/                     ← Logos de marcas
├── products/                   ← Imágenes de productos
├── hero/                       ← Slides del hero principal
├── brochures/                  ← PDFs de rotafolios
├── news/                       ← Imágenes de noticias
└── job-openings/              ← Imágenes de ofertas laborales

resources/views/
├── layouts/
│   ├── public.blade.php        ← Layout público (header + footer + badge flotante)
│   ├── app.blade.php           ← Layout admin (sidebar con Ofertas Laborales)
│   └── auth.blade.php          ← Layout auth
├── public/
│   ├── home.blade.php          ← Home (slider hero, categorías, productos, marcas, oficinas)
│   ├── about.blade.php         ← Storytime con timeline moderno
│   ├── work-with-us.blade.php  ← Ofertas laborales públicas ★
│   ├── contact.blade.php       ← Contacto
│   ├── products/               ← Catálogo de productos
│   ├── brands/                 ← Marcas (index + show)
│   └── news/                   ← Noticias (index + show)
├── livewire/
│   ├── admin/
│   │   ├── brands/             ← BrandForm, BrandIndex
│   │   ├── hero/               ← HeroSlideForm, HeroSlideIndex
│   │   ├── products/           ← ProductForm, ProductIndex
│   │   ├── job-openings/       ← JobOpeningForm, JobOpeningIndex ★
│   │   ├── settings/           ← SettingsForm
│   │   └── ...
│   └── public/
│       └── search/
│           └── product-search.blade.php  ← Search compacto con Alpine.js

app/Livewire/Admin/
├── Brands/BrandForm.php              ← _finishUpload override
├── Hero/HeroSlideForm.php            ← _finishUpload override
├── Products/ProductForm.php          ← _finishUpload override
├── Settings/SettingsForm.php         ← Social links + work_with_us + $isSimple
├── JobOpenings/
│   ├── JobOpeningIndex.php           ← Listado con search + paginación ★
│   └── JobOpeningForm.php            ← Form con upload + _finishUpload ★

app/Models/
├── JobOpening.php                    ← Modelo con scopes, casts, accessors ★

app/Policies/
├── JobOpeningPolicy.php              ← Policy con permisos view content / manage news ★

app/Services/
└── FileUploader.php                  ← upload() + delete()

routes/
└── web.php                           ← 3 rutas job-openings + rutas públicas
```

---

## 📋 Orden de implementación (para repetir desde cero)

Si quisieras reproducir todo desde un proyecto limpio de Laravel + Livewire + Flux:

```
FASE 1 — FUNDACIÓN TÉCNICA
  01. Corregir upload de imágenes (override _finishUpload en cada Form)
  02. Corregir null-safe operators en Livewire forms admin
  03. Corregir policies (ArgumentCountError)
  04. Corregir multiple root elements
  05. Corregir settings (agregar $isSimple)

FASE 2 — NUEVOS MÓDULOS
  06. Crear JobOpening (migración, modelo, factory, seeder, policy)
  07. Crear JobOpening Livewire admin (index + form + rutas + sidebar)
  08. Crear vista pública work-with-us
  09. Agregar redes sociales a Settings + footer dinámico

FASE 3 — REWORK VISUAL
  10. Configurar imagen de fondo (fondo-main.jpg) + overlays en home hero
  11. Agregar galardones (stats circulares con iconos SVG)
  12. Agregar Bolivia Flag ribbon con animación waveRibbon
  13. Configurar AOS con efectos variados
  14. Rework hero (glassmorphism, layout expandido, logo seguro)
  15. Rework storytime (timeline moderno, hero compacto)
  16. Rework navbar (eliminar Inicio, search compacto con Alpine.js)

FASE 4 — DETALLES
  17. Quitar grayscale de marcas (/marcas)
  18. Agregar badge "Hecho en Bolivia" flotante
  19. Agregar "Ofertas Laborales" al nav público
```

---

## 📝 Notas importantes

### Sobre SettingsForm
Actualmente las keys en `mount()` son solo las 8 originales. Si se agregaron `work_with_us_title`, `work_with_us_description` y `social_*` al `save()` pero NO al array de `mount()`, estos campos no se precargarán al abrir la página de configuración. Pendiente de verificar.

### Sobre imágenes de storage
- `hecho_en_bolivia.jpg` no existe — el archivo real es `hecho_en_bolivia.png`. La referencia en `public.blade.php` debe apuntar a `.png`.
- El resto de imágenes existen y se cargan correctamente.

### Sobre permisos
- `view content` y `manage news` son permisos de Spatie Permission. Asegurarse de que existen en el seeder de permisos.
- JobOpeningPolicy usa estos mismos permisos para mantener consistencia.

---

*Plan generado el 17 de julio de 2026 — documenta todo el trabajo realizado hasta la fecha.*
