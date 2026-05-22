<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DashboardServiceTest extends TestCase
{
    private mysqli $con;
    private DashboardService $dashboard;

    protected function setUp(): void
    {
        $this->con = TestDatabase::connection();
        $this->dashboard = new DashboardService($this->con);
    }

    public function testOverviewStructure(): void
    {
        $data = $this->dashboard->overview('30');
        $this->assertArrayHasKey('kpis', $data);
        $this->assertArrayHasKey('profit', $data['kpis']);
        $this->assertArrayHasKey('earnings', $data['kpis']);
        $this->assertArrayHasKey('quantity_sold', $data['kpis']);
        $this->assertArrayHasKey('transactions', $data['kpis']);
        $this->assertArrayHasKey('today', $data);
        $this->assertArrayHasKey('activity', $data);
        $this->assertArrayHasKey('by_category', $data);
        $this->assertIsArray($data['monthly']);
        $this->assertCount(12, $data['monthly']);
    }

    public function testDailySalesReturnsSeriesForCurrentMonth(): void
    {
        $m = (int) date('n');
        $y = (int) date('Y');
        $data = $this->dashboard->dailySales($m, $y);
        $this->assertArrayHasKey('categories', $data);
        $this->assertArrayHasKey('series', $data);
        $this->assertCount(count($data['categories']), $data['series']);
    }

    public function testSummaryMatchesOverviewKpis(): void
    {
        $summary = $this->dashboard->summary('30');
        $overview = $this->dashboard->overview('30');
        $this->assertSame($overview['kpis']['earnings'], $summary['revenue']);
        $this->assertSame($overview['kpis']['quantity_sold'], $summary['units_sold']);
    }
}
