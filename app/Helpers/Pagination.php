<?php

namespace App\Helpers;

class Pagination
{
    private int $totalItems;
    private int $itemsPerPage;
    private int $currentPage;
    private string $url;
    private int $totalPages;

    public function __construct(int $totalItems, int $itemsPerPage, int $currentPage, string $url)
    {
        $this->totalItems   = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage  = max(1, $currentPage);
        $this->url          = $url;
        $this->totalPages   = ceil($totalItems / $itemsPerPage);
    }

    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    public function getLinks(): string
    {
        if ($this->totalPages <= 1) {
            return '';
        }

        $html = '<div class="pagination" style="display: flex; gap: 5px; margin-top: 20px;">';

        // Helper to build URL
        $buildUrl = function ($page) {
            return strpos($this->url, '?') !== false
                ? "{$this->url}&page={$page}"
                : "{$this->url}?page={$page}";
        };

        // PREVIOUS
        if ($this->currentPage > 1) {
            $prev = $this->currentPage - 1;
            $html .= '<a href="' . $buildUrl($prev) . '" class="btn" style="background:#ddd; color:#333;">&laquo; Prev</a>';
        }

        // NUMBERS
        for ($i = 1; $i <= $this->totalPages; $i++) {
            $activeStyle = $i === $this->currentPage
                ? 'background:#4b6cb7; color:#fff;'
                : 'background:#f1f1f1; color:#333;';
            
            $html .= '<a href="' . $buildUrl($i) . '" class="btn" style="' . $activeStyle . '">' . $i . '</a>';
        }

        // NEXT
        if ($this->currentPage < $this->totalPages) {
            $next = $this->currentPage + 1;
            $html .= '<a href="' . $buildUrl($next) . '" class="btn" style="background:#ddd; color:#333;">Next &raquo;</a>';
        }

        $html .= '</div>';

        return $html;
    }
}
