<?php
namespace App\helpers;

class Controller
{
    public function sanitize(string $value): string
    {
        return htmlspecialchars(trim(string: $value), ENT_QUOTES, 'UTF-8');
    }

    public function paginate(array $items, int $page = 1, int $perPage = 6): array
    {
        $total = count($items);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        return [
            'items' => array_slice($items, $offset, $perPage),
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ];
    }

}