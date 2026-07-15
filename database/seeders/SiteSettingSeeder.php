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
                'Laboratorios Delta fue fundada el año 1987 por los esposos Jorge E. Claros Rojas y Gladys M. Fuentes Dubravcic. con la finalidad de llegar con productos de alta calidad a todo el mercado boliviano. El inicio de la empresa fue lento, pero constante. Es en los años 90 que la empresa pudo realizar nuevas inversiones consiguiendo de esta manera romper la barrera del punto de quiebre que frenaba a la empresa y hace de esta una industria en desarrollo.',
                'Hacia finales de los años 90 Laboratorios Delta se consolida como una industria importante en el mercado farmacéutico de Bolivia, llegando a todos los departamentos del país a través de sus diferentes distribuidores.',
                'Es a fines de los años 90 e inicios de los años 2000 que se incorporan a trabajar los hijos de los fundadores tras el fallecimiento de la Dra. Gladys Fuentes. Estos tres nuevos profesionales aportan con sus nuevos conocimientos y su energía renovada, marcando una nueva segunda etapa de desarrollo de Laboratorios Delta. Concretando grandes y nuevos proyectos: La construcción de una nueva fábrica con tecnología española en la zona de Tilata municipio Viacha, el desarrollo de un nuevo laboratorio de Control de Calidad con última tecnología de punta, la construcción de un nuevo edificio para oficinas administrativas y almacenes para la ciudad de La Paz en la zona de Sopocachi, la apertura de Sucursales propias en los departamentos de Santa Cruz, Cochabamba y Sucre desde los cuales se atienden todas las ciudades y provincias del país, el desarrollo y formulación de más de 70 nuevos productos y la certificación BPM y BPA en todas sus sucursales.',
                'Es así que a la fecha Laboratorio Delta cuenta con más de 35 años de experiencia, brindando medicamentos de la más alta calidad al mercado boliviano, innovando en tecnología, capacitando permanentemente a su personal, trabajando en planes estratégicos para seguir creciendo y convertirse en una de las principales industrias del país; buscando de este modo aportar al crecimiento de nuestra Bolivia.',
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
