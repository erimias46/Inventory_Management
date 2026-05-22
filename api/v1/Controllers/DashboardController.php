<?php

declare(strict_types=1);

final class DashboardController
{
    public function __construct(private DashboardService $dashboard) {}

    public function dailySales(): void
    {
        $month = (int) ($_GET['month'] ?? date('n'));
        $year = (int) ($_GET['year'] ?? date('Y'));
        ApiResponse::success($this->dashboard->dailySales($month, $year));
    }

    public function summary(): void
    {
        $period = $_GET['period'] ?? '30';
        ApiResponse::success($this->dashboard->summary($period));
    }
}
