<?php
namespace App\helpers;

class Controller
{
    public function sanitize(string $value): string
    {
        return htmlspecialchars(trim(string: $value), ENT_QUOTES, 'UTF-8');
    }

    public function paginate(array $items, int $page = 1, int $perPage = 6, int $range = 2): array
    {
        $total = count($items);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $start = max(1, $page - $range);
        $end = min($totalPages, $page + $range);

        $pages = [];

        if ($start > 1) {
            $pages[] = 1;
            if ($start > 2) {
                $pages[] = "..."; 
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $pages[] = "...";
            }
            $pages[] = $totalPages;
        }

        return [
            'items' => array_slice($items, $offset, $perPage),
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'pages' => $pages
        ];
    }

}