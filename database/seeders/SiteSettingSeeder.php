<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::put('about_history', [
            'title' => 'Nuestra historia',
            'body' => implode("\n\n", [
                'En 1987, los esposos Jorge E. Claros Rojas y Gladys M. Fuentes Dubravcic hicieron realidad un sueño compartido: fundar Laboratorios Delta con la firme convicción de llevar medicamentos de alta calidad a cada rincón de Bolivia. Los primeros pasos fueron modestos, impulsados por la perseverancia y el compromiso. Pero el camino, aunque lento, nunca se detuvo. Fue en la década de los 90 cuando la empresa logró dar un salto decisivo: nuevas inversiones permitieron romper el punto de quiebre que la frenaba, transformando aquel pequeño emprendimiento en una industria farmacéutica en pleno desarrollo.',
                'Hacia el final de los 90, Laboratorios Delta ya era un nombre respetado en el mercado farmacéutico boliviano. Sus productos cruzaban las fronteras departamentales y llegaban a todo el país a través de una red de distribuidores que crecía sin pausa. La calidad, el esfuerzo y la cercanía con el cliente se convirtieron en el sello de la casa.',
                'La vida, sin embargo, también trajo momentos difíciles. Tras el fallecimiento de la Dra. Gladys Fuentes, sus tres hijos tomaron la posta con la fuerza y el conocimiento de una nueva generación. Su llegada marcó el inicio de una segunda etapa de expansión sin precedentes: una moderna fábrica con tecnología española en Tilata (Viacha), un laboratorio de Control de Calidad equipado con la más alta tecnología, un nuevo edificio corporativo en Sopocachi — La Paz, sucursales propias en Santa Cruz, Cochabamba y Sucre, más de 70 nuevos productos formulados y, finalmente, la certificación en Buenas Prácticas de Manufactura y Almacenamiento en todas las sucursales.',
                'Hoy, con más de 35 años de trayectoria, Laboratorios Delta sigue mirando hacia adelante. La innovación tecnológica, la capacitación permanente de su equipo y una planificación estratégica ambiciosa son los pilares que nos impulsan a seguir creciendo, con la meta clara de convertirnos en una de las principales industrias del país. Porque cada medicamento que entregamos no es solo un producto: es nuestro aporte a la salud y al futuro de Bolivia.',
            ]),
        ], 'about');

        SiteSetting::put('about_mission', [
            'title' => 'Misión',
            'body' => 'La misión de Laboratorios Delta es proveer de medicamentos al mercado Boliviano con la mejor calidad posible a un precio razonable. Al mismo tiempo la empresa debe asegurar una estabilidad y bienestar económico para ambos accionistas y trabajadores de la empresa.',
        ], 'about');

        SiteSetting::put('about_vision', [
            'title' => 'Visión',
            'body' => 'Laboratorios Delta debe convertirse en una empresa líder del mercado farmacéutico, líder en ventas, líder en tecnología y líder en servicios. Laboratorios Delta debe convertirse en una empresa ecológicamente sostenible donde se aproveche al máximo los recursos obtenidos. Laboratorios Delta debe convertirse en una empresa con un alto grado de responsabilidad social, devolviendo a la sociedad el apoyo recibido. Laboratorios Delta debe formar un equipo de trabajo sólido, unido, responsable y activo que sea capaz de afrontar los desafíos tecnológicos del nuevo milenio.',
        ], 'about');

        SiteSetting::put('about_values', [
            'title' => 'Valores',
            'body' => 'Como empresa y como personas debemos constituirnos en el ejemplo a seguir por el resto de la sociedad. Los valores que se fomentarán en Laboratorios Delta son: La Honradez, la Consideración, la Compasión, la Camaradería, el trabajo en equipo, la Paciencia, el Esmero, la buena voluntad, el buen humor, la Integridad y la Responsabilidad.',
            'quote' => '"No encuentres la falla, encuentra el remedio"',
        ], 'about');

        SiteSetting::put('about_quality_policy', [
            'title' => 'Política de Calidad',
            'body' => 'Laboratorios Delta S.A., es una industria farmacéutica dedicada a la formulación, fabricación y comercialización de medicamentos de calidad a precios al alcance de la población. Estamos comprometidos con la calidad de nuestros productos; utilizando materiales con altos estándares, aplicando las buenas prácticas de manufactura dentro de todos nuestros procesos y fortaleciendo la cultura de seguridad industrial y salud ocupacional en nuestro personal. Nuestro compromiso de mejora continua, se refleja en la inversión constante de nuestros accionistas en infraestructura y tecnología innovadora, en la capacitación permanente a nuestro personal y en el perfeccionamiento de nuestros procesos de producción, para garantizar productos que brinden salud y bienestar a nuestros clientes.',
        ], 'about');

        SiteSetting::put('legal_notice', [
            'body' => 'Todos los documentos, imágenes e información de esta página web son propiedad de Laboratorio Químico Farmacéutico Industrial Delta S.A. salvo se indique expresamente lo contrario. No está permitido el uso parcial o total de ninguna información sin consentimiento expreso de Laboratorio Químico Farmacéutico Industrial Delta S.A.',
        ], 'general');

        SiteSetting::put('company_name', 'Laboratorios Delta S.A.', 'general');
        SiteSetting::put('company_slogan', 'Encuentra el Producto que deseas', 'general');
    }
}
