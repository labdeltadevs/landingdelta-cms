<?php

use App\Support\VademecumHtml;

it('construye la tabla con etiqueta y contenido', function () {
    $html = VademecumHtml::build([
        ['label' => 'Presentación', 'text' => 'Caja con 24 cápsulas'],
        ['label' => 'Indicaciones', 'text' => 'Infecciones respiratorias'],
    ]);

    expect($html)->toBe(
        '<table class="table-vademecum">'
        .'<tr><td>Presentación</td><td>Caja con 24 cápsulas</td></tr>'
        .'<tr><td>Indicaciones</td><td>Infecciones respiratorias</td></tr>'
        .'</table>'
    );
});

it('omite filas con contenido vacio', function () {
    $html = VademecumHtml::build([
        ['label' => 'Presentación', 'text' => ''],
        ['label' => 'Composición', 'text' => '   '],
        ['label' => 'Indicaciones', 'text' => 'Infecciones'],
    ]);

    expect($html)->toContain('Infecciones')
        ->not->toContain('Presentación')
        ->not->toContain('Composición');
});

it('devuelve null si todo el contenido esta vacio', function () {
    expect(VademecumHtml::build([['label' => 'Dosis', 'text' => '']]))
        ->toBeNull();
});

it('convierte saltos de linea en <br>', function () {
    $html = VademecumHtml::build([
        ['label' => 'Composición', 'text' => "Linea uno\r\nLinea dos\rLinea tres"],
    ]);

    expect($html)->toContain('Linea uno<br>Linea dos<br>Linea tres');
});

it('escapa el HTML de entrada', function () {
    $html = VademecumHtml::build([
        ['label' => 'Indicaciones', 'text' => '<script>alert("x")</script> & más'],
    ]);

    expect($html)->toContain('&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt; &amp; más');
});
