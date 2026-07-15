<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::query()->create([
            'title' => 'Certificación BPM para todas nuestras sucursales',
            'slug' => Str::slug('Certificación BPM para todas nuestras sucursales'),
            'excerpt' => 'Laboratorios Delta S.A. ha obtenido la certificación en Buenas Prácticas de Manufactura para todas sus sucursales a nivel nacional.',
            'body' => implode("\n\n", [
                'Nos complace anunciar que Laboratorios Delta S.A. ha completado exitosamente el proceso de certificación en Buenas Prácticas de Manufactura (BPM) para todas nuestras sucursales a nivel nacional.',
                'Este logro representa años de trabajo continuo y dedicación a la calidad, reafirmando nuestro compromiso con la excelencia en la producción de medicamentos para el pueblo boliviano.',
                'La certificación BPM abarca todos los procesos de producción, almacenamiento y distribución, garantizando que cada producto que llega a sus manos cumple con los más altos estándares internacionales de calidad.',
            ]),
            'published_at' => now()->subDays(5),
            'is_active' => true,
            'sort' => 0,
        ]);

        News::query()->create([
            'title' => 'Nueva línea de productos pediátricos',
            'slug' => Str::slug('Nueva línea de productos pediátricos'),
            'excerpt' => 'Presentamos nuestra nueva línea de productos especialmente formulados para el cuidado de la salud infantil.',
            'body' => implode("\n\n", [
                'Laboratorios Delta S.A. lanza al mercado una nueva línea de productos pediátricos, diseñados específicamente para atender las necesidades de salud de los más pequeños del hogar.',
                'Nuestra nueva línea incluye presentaciones en jarabes, suspensiones y gotas con sabores agradables que facilitan la administración a los niños, manteniendo la más alta calidad y eficacia terapéutica que nos caracteriza.',
                'Con más de 35 años de experiencia en el mercado farmacéutico boliviano, seguimos innovando para brindar soluciones de salud accesibles y de calidad para toda la familia.',
            ]),
            'published_at' => now()->subDays(15),
            'is_active' => true,
            'sort' => 1,
        ]);
    }
}
