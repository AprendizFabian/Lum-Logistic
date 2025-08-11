<?php
namespace App\Models;

use Google\Client;
use Google\Service\Sheets;

class SheetsModel
{
    private $spreadsheetId = '1_RzVtWiIzV5nL9GcnbvWI0rdbl4K7WPJfHkW5CLLFQk';
    private $range = 'catalogo';
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Tu Proyecto');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(__DIR__ . '/../../public/sheetsconexion-f0e1890e48bc.json');
        $this->client->setAccessType('offline');
    }

    public function getData($page = 1, $perPage = 6, $search = '')
    {
        $service = new Sheets($this->client);
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $this->range);
        $values = $response->getValues();

        if (!$values)
            return ['data' => [], 'headers' => [], 'total' => 0];

        $headers = array_shift($values);
        if ($search !== '') {
            $values = array_filter($values, function ($row) use ($search) {
                return stripos(implode(' ', $row), $search) !== false;
            });
            $values = array_values($values);
        }

        $total = count($values);
        $totalPages = ceil($total / $perPage);
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        $paged = array_slice($values, $offset, $perPage);

        return [
            'headers' => $headers,
            'data' => $paged,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'startIndex' => $offset,
            'searchParam' => $search !== '' ? '&search=' . urlencode($search) : '',
            'startPage' => max(1, $page - 2),
            'endPage' => min($totalPages, $page + 2),
        ];
    }
    public function getVidaUtil($page = 1, $perPage = 6, $search = '')
    {
        $range = 'V.U';
        $service = new Sheets($this->client);
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues();

        if (!$values)
            return ['data' => [], 'headers' => [], 'total' => 0];

        $headers = array_shift($values);

        if ($search !== '') {
            $values = array_filter($values, function ($row) use ($search) {
                return stripos(implode(' ', $row), $search) !== false;
            });
            $values = array_values($values);
        }

        $total = count($values);
        $totalPages = ceil($total / $perPage);
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        $paged = array_slice($values, $offset, $perPage);

        return [
            'headers' => $headers,
            'data' => $paged,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'startIndex' => $offset,
            'searchParam' => $search !== '' ? '&search=' . urlencode($search) : '',
            'startPage' => max(1, $page - 2),
            'endPage' => min($totalPages, $page + 2),
        ];
    }
    public function getFechasDesdeOtroArchivo($range = 'Fechas', $page = 1, $perPage = 6, $search = '')
    {
        try {
            $service = new Sheets($this->client);
            $fullRange = $range;
            $response = $service->spreadsheets_values->get($this->spreadsheetId, $fullRange);
            $values = $response->getValues();

            if (empty($values))
                return ['data' => [], 'headers' => [], 'total' => 0];

            $headers = array_shift($values);

            if ($search !== '') {
                $values = array_values(array_filter(
                    $values,
                    fn($row) => stripos(implode(' ', $row), $search) !== false
                ));
            }

            $total = count($values);
            $offset = ($page - 1) * $perPage;

            return [
                'headers' => $headers,
                'data' => array_slice($values, $offset, $perPage),
                'total' => $total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'totalPages' => ceil($total / $perPage),
                'startIndex' => $offset,
                'searchParam' => $search ? '&buscar=' . urlencode($search) : '',
                'startPage' => max(1, $page - 2),
                'endPage' => min(ceil($total / $perPage), $page + 2)
            ];
        } catch (\Exception $e) {
            error_log('Error en getFechasDesdeOtroArchivo: ' . $e->getMessage());
            return [
                'data' => [],
                'headers' => [],
                'total' => 0,
                'error' => 'No se pudo obtener los datos'
            ];
        }
    }

    public function obtenerDatosDesdeSheets($ean)
    {
        $service = new Sheets($this->client);

        // === Leer hoja "validador"
        $validadorResponse = $service->spreadsheets_values->get($this->spreadsheetId, 'validador!A1:Z50000');
        $validadorValues = $validadorResponse->getValues();
        if (!$validadorValues)
            return ['error' => 'No se pudo obtener la hoja validador'];

        // Normalizar encabezados
        $headers = array_map('trim', $validadorValues[0]);
        $headersLower = array_map('mb_strtolower', $headers);

        $indexEAN = array_search('ean', $headersLower);
        $indexCategoria = array_search('categoría', $headersLower);
        $indexDescripcion = 2; // Columna C → índice 2 si A=0, B=1, C=2

        if ($indexEAN === false || $indexCategoria === false) {
            return ['error' => 'Encabezados incorrectos en hoja validador'];
        }

        // === Buscar EAN y obtener categoría + descripción
        $categoria = null;
        $descripcion = null;

        foreach (array_slice($validadorValues, 1) as $row) {
            if (isset($row[$indexEAN]) && trim($row[$indexEAN]) === trim($ean)) {
                $categoria = $row[$indexCategoria] ?? null;
                $descripcion = $row[$indexDescripcion] ?? '';
                break;
            }
        }

        if (!$categoria) {
            return [
                'diasVidaUtil' => null,
                'observacion' => 'Categoría no encontrada',
                'descripcion' => $descripcion
            ];
        }

        // === Leer hoja "V.U"
        $vuResponse = $service->spreadsheets_values->get($this->spreadsheetId, 'V.U!A1:Z30000');
        $vuValues = $vuResponse->getValues();
        if (!$vuValues)
            return ['error' => 'No se pudo obtener la hoja V.U'];

        // Buscar encabezado de columna "SALIDA POR MERMA"
        $headersVU = array_map('trim', $vuValues[0]);
        $headersVULower = array_map('mb_strtolower', $headersVU);

        $indexCategoriaVU = 0; // Categoría en columna A
        $indexDias = array_search('salida por merma', $headersVULower);

        if ($indexDias === false) {
            return [
                'diasVidaUtil' => null,
                'observacion' => 'Columna SALIDA POR MERMA no encontrada',
                'descripcion' => $descripcion
            ];
        }

        // === Buscar coincidencia de categoría
        foreach (array_slice($vuValues, 1) as $row) {
            if (!isset($row[$indexCategoriaVU]))
                continue;

            $catVU = $this->normalizarTexto($row[$indexCategoriaVU]);
            $catBuscada = $this->normalizarTexto($categoria);

            if ($catVU === $catBuscada) {
                $dias = isset($row[$indexDias]) ? (int) $row[$indexDias] : null;
                return [
                    'diasVidaUtil' => $dias,
                    'observacion' => null,
                    'categoria' => $categoria,
                    'descripcion' => $descripcion
                ];
            }
        }

        return [
            'diasVidaUtil' => null,
            'observacion' => 'Categoría sin vida útil',
            'descripcion' => $descripcion
        ];
    }

    private function normalizarTexto($texto)
    {
        $texto = mb_strtolower(trim($texto), 'UTF-8');
        $texto = strtr($texto, [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
            'ä' => 'a',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'o',
            'ü' => 'u'
        ]);
        $texto = preg_replace('/[^a-z0-9]/', '', $texto); // Elimina símbolos, espacios, guiones
        return $texto;
    }

    public function buscarColumnaPorEan($ean, $columnaIndice)
    {
        $service = new Sheets($this->client);
        $validadorResponse = $service->spreadsheets_values->get($this->spreadsheetId, 'validador');
        $values = $validadorResponse->getValues();

        if (!$values)
            return null;

        $headers = array_map('mb_strtolower', array_map('trim', $values[0]));
        $indexEAN = array_search('ean', $headers);

        foreach (array_slice($values, 1) as $row) {
            if (isset($row[$indexEAN]) && trim($row[$indexEAN]) === trim($ean)) {
                return $row[$columnaIndice] ?? null;
            }
        }

        return null;
    }

}

