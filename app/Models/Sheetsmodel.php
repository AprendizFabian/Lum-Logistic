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

    public function getData($page = 1, $perPage = 10)
    {
        $service = new Sheets($this->client);
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $this->range);
        $values = $response->getValues();

        // Eliminar encabezados y paginar
        $headers = array_shift($values);
        $offset = ($page - 1) * $perPage;
        $paged = array_slice($values, $offset, $perPage);

        return [
            'headers' => $headers,
            'data' => $paged,
            'total' => count($values),
            'perPage' => $perPage,
            'currentPage' => $page
        ];
    }
}
