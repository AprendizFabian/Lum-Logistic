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

    if (!$values) return ['data' => [], 'headers' => [], 'total' => 0];

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

    if (!$values) return ['data' => [], 'headers' => [], 'total' => 0];

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

        if (empty($values)) return ['data' => [], 'headers' => [], 'total' => 0];

        $headers = array_shift($values);
        
        if ($search !== '') {
            $values = array_values(array_filter($values, 
                fn($row) => stripos(implode(' ', $row), $search) !== false));
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
            'searchParam' => $search ? '&buscar='.urlencode($search) : '',
            'startPage' => max(1, $page - 2),
            'endPage' => min(ceil($total / $perPage), $page + 2)
        ];
    } catch (\Exception $e) {
        error_log('Error en getFechasDesdeOtroArchivo: '.$e->getMessage());
        return [
            'data' => [], 
            'headers' => [], 
            'total' => 0,
            'error' => 'No se pudo obtener los datos'
        ];
    }
}
}