<?php

namespace App\Support;

final class VademecumHtml
{
    /**
     * Construye una tabla HTML de dos columnas (etiqueta | contenido) con las
     * secciones del vademecum. Omite filas vacias y devuelve null si no queda
     * contenido aprovechable.
     *
     * @param  array<int, array{label: string, text: string}>  $rows
     */
    public static function build(array $rows): ?string
    {
        $cells = '';

        foreach ($rows as $row) {
            $text = str_replace(["\r\n", "\r"], "\n", trim($row['text']));

            if ($text === '') {
                continue;
            }

            $cells .= '<tr>'
                .'<td>'.e(trim($row['label'])).'</td>'
                .'<td>'.str_replace("\n", '<br>', e($text)).'</td>'
                .'</tr>';
        }

        return $cells === '' ? null : '<table class="table-vademecum">'.$cells.'</table>';
    }

    /**
     * Extrae las filas (etiqueta | contenido) de una tabla vademecum existente
     * para poder reeditarlas en el formulario. Devuelve un arreglo vacio si el
     * HTML no contiene una tabla con la clase table-vademecum.
     *
     * @return array<int, array{label: string, text: string}>
     */
    public static function parse(?string $html): array
    {
        if ($html === null || trim($html) === '') {
            return [];
        }

        if (! preg_match('/<table\s+class=["\']table-vademecum["\'][^>]*>(.*?)<\/table>/is', $html, $table)) {
            return [];
        }

        $rows = [];

        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $table[1], $trs);

        foreach ($trs[1] as $tr) {
            if (! preg_match_all('/<td[^>]*>(.*?)<\/td>/is', $tr, $tds) || count($tds[1]) < 2) {
                continue;
            }

            $rows[] = [
                'label' => self::cleanCell($tds[1][0]),
                'text' => self::cleanCell($tds[1][1]),
            ];
        }

        return $rows;
    }

    private static function cleanCell(string $cell): string
    {
        $cell = preg_replace('/<br\s*\/?>/i', "\n", $cell) ?? $cell;
        $cell = trim(strip_tags($cell));

        return html_entity_decode($cell, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
