<?php
namespace App\Models;

use Google\Client;
use Google\Service\Sheets;

class SheetsModel
{
    private $spreadsheetId = '1_RzVtWiIzV5nL9GcnbvWI0rdbl4K7WPJfHkW5CLLFQk';
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Tu Proyecto');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(__DIR__ . '/../../public/sheetsconexion-f0e1890e48bc.json');
        $this->client->setAccessType('offline');
    }

    public function getDatesFromSheets($range = 'Fechas!A:B', $search = '')
    {
        $service = new Sheets($this->client);
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues() ?? [];

        if (empty($values)) {
            return ['headers' => [], 'data' => []];
        }

        $headers = array_shift($values);

        if ($search !== '') {
            $values = array_values(array_filter($values, function ($row) use ($search) {
                return stripos(implode(' ', $row), $search) !== false;
            }));
        }

        return [
            'headers' => $headers,
            'data' => $values,
            'error' => null
        ];
    }
}

