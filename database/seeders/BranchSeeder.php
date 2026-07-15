<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::query()->insert([
            [
                'name' => 'Casa Matriz',
                'city' => 'La Paz',
                'address' => 'Calle Presbítero Medina, Pasaje Tal Tal Nro. 2, Zona Sopocachi',
                'phone' => '2 2411516',
                'phone_2' => '69829357',
                'email' => 'ventaslpz@laboratoriosdelta.net',
                'is_active' => true,
                'sort' => 0,
            ],
            [
                'name' => 'Sucursal Santa Cruz',
                'city' => 'Santa Cruz',
                'address' => 'Avenida 26 de Febrero Nro. 636, Zona Segundo Anillo',
                'phone' => '3262137',
                'phone_2' => '72168358',
                'email' => 'ventasscz@laboratoriosdelta.net',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'name' => 'Sucursal Cochabamba',
                'city' => 'Cochabamba',
                'address' => 'Calle Tarija Esquina Portales #1408, Queru Queru',
                'phone' => '4140462',
                'phone_2' => '67405928',
                'email' => 'ventascbba@laboratoriosdelta.net',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'name' => 'Sucursal Chuquisaca',
                'city' => 'Chuquisaca',
                'address' => 'Calle José Prudencio Bustillos Nro. 725, Edificio S/N PB Dpto. SN, Zona San Juanillo Bajo',
                'phone' => '6436940',
                'phone_2' => '76400013',
                'email' => 'ventascbba@laboratoriosdelta.net',
                'is_active' => true,
                'sort' => 3,
            ],
        ]);
    }
}
