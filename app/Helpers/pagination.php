<?php

namespace App\Helpers;

class Pagination
{
    public static function paginate(
        int $totalRecords,
        int $perPage,
        int $currentPage,
        string $baseUrl
    ): array {
        $totalPages = (int)ceil($totalRecords / $perPage);

        return [
            'total_records' => $totalRecords,
            'per_page'      => $perPage,
            'current_page'  => $currentPage,
            'total_pages'   => $totalPages,
            'offset'        => ($currentPage - 1) * $perPage,
            'base_url'      => $baseUrl
        ];
    }
}
