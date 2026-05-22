<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SaleServiceTest extends TestCase
{
    private mysqli $con;
    private SaleService $sales;

    protected function setUp(): void
    {
        $this->con = TestDatabase::connection();
        $this->sales = new SaleService($this->con);
    }

    public function testDeliveryRequiresReason(): void
    {
        $result = $this->sales->processMultiSale(1, [
            ['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 50, 'cash' => 50, 'bank' => 0, 'quantity' => 1],
        ], 'delivery', '');
        $this->assertSame(0, $result['success_count']);
        $this->assertNotEmpty($result['errors']);
        $this->assertSame('reason_required', $result['errors'][0]['error'] ?? '');
    }

    public function testShopSaleDecrementsStock(): void
    {
        $before = TestDatabase::scalar("SELECT quantity FROM jeans WHERE jeans_name='Test Jean' AND size='M' LIMIT 1");
        $result = $this->sales->processMultiSale(1, [
            ['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 100, 'cash' => 100, 'bank' => 0, 'quantity' => 1],
        ], 'shop');
        $this->assertGreaterThanOrEqual(1, $result['success_count']);
        $after = TestDatabase::scalar("SELECT quantity FROM jeans WHERE jeans_name='Test Jean' AND size='M' LIMIT 1");
        $this->assertSame($before - 1, $after);
    }
}
