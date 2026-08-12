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

it('parsea una tabla vademecum de vuelta a filas', function () {
    $html = VademecumHtml::build([
        ['label' => 'Presentación', 'text' => "Caja con 24 cápsulas\nCaja x 30"],
        ['label' => 'Composición', 'text' => 'Gabapentina 300 mg'],
    ]);

    expect(VademecumHtml::parse($html))->toBe([
        ['label' => 'Presentación', 'text' => "Caja con 24 cápsulas\nCaja x 30"],
        ['label' => 'Composición', 'text' => 'Gabapentina 300 mg'],
    ]);
});

it('decodifica entidades y convierte <br> en saltos de linea al parsear', function () {
    $html = '<table class="table-vademecum">'
        .'<tr><td>Indicaciones</td><td>&lt;script&gt; &amp; más<br>Segunda línea</td></tr>'
        .'</table>';

    expect(VademecumHtml::parse($html))->toBe([
        ['label' => 'Indicaciones', 'text' => "<script> & más\nSegunda línea"],
    ]);
});

it('devuelve un arreglo vacio si el html no es una tabla vademecum', function () {
    expect(VademecumHtml::parse(null))->toBe([])
        ->and(VademecumHtml::parse(''))->toBe([])
        ->and(VademecumHtml::parse('<p>Texto plano</p>'))->toBe([]);
});

it('redondea parse -> build sin perder contenido', function () {
    $rows = [
        ['label' => 'Presentación', 'text' => "Blister Alu-Alu x 10\nCaja x 30 cápsulas"],
        ['label' => 'Posología', 'text' => 'Dosis inicial: 300 mg/día'],
    ];

    expect(VademecumHtml::parse(VademecumHtml::build($rows)))->toBe($rows);
});
