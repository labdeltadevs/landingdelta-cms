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
}
