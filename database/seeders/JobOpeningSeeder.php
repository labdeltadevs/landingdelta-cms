<?php

namespace Database\Seeders;

use App\Models\JobOpening;
use Illuminate\Database\Seeder;

class JobOpeningSeeder extends Seeder
{
    public function run(): void
    {
        JobOpening::factory()->create([
            'title' => 'Farmacéutico/a — Santa Cruz',
            'description' => "Buscamos profesionales farmacéuticos comprometidos con la calidad y el servicio.\n\nRequisitos:\n- Título en Bioquímica y Farmacia\n- Experiencia mínima de 2 años\n- Disponibilidad horaria\n- Residencia en Santa Cruz\n\nOfrecemos:\n- Salario competitivo\n- Beneficios de ley\n- Oportunidades de crecimiento",
            'application_email' => 'rrhh@laboratoriosdelta.net',
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addMonths(2)->format('Y-m-d'),
        ]);

        JobOpening::factory()->create([
            'title' => 'Visitador Médico — La Paz',
            'description' => "Importante laboratorio farmacéutico solicita visitador médico para la ciudad de La Paz.\n\nRequisitos:\n- Formación en Ciencias de la Salud o Marketing Farmacéutico\n- Experiencia en visitación médica\n- Conocimiento del mercado farmacéutico local\n- Vehículo propio\n\nOfrecemos:\n- Comisiones atractivas\n- Capacitación continua\n- Estabilidad laboral",
            'application_email' => 'rrhh@laboratoriosdelta.net',
            'valid_from' => now()->addDays(5)->format('Y-m-d'),
            'valid_until' => now()->addMonths(3)->format('Y-m-d'),
        ]);

        JobOpening::factory()->create([
            'title' => 'Asistente Administrativo — Cochabamba',
            'description' => "Nos encontramos en la búsqueda de un/a asistente administrativo para nuestras oficinas centrales en Cochabamba.\n\nRequisitos:\n- Técnico Superior o Licenciatura en Administración de Empresas\n- Manejo de paquetes ofimáticos\n- Experiencia en labores administrativas\n- Organización y proactividad\n\nOfrecemos:\n- Ambiente laboral agradable\n- Horario flexible\n- Beneficios sociales",
            'application_email' => 'rrhh@laboratoriosdelta.net',
            'valid_from' => now()->subDays(3)->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
        ]);
    }
}
