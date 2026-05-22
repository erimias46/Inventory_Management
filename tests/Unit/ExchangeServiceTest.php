<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ExchangeServiceTest extends TestCase
{
    private mysqli $con;
    private SaleService $sales;
    private ExchangeService $exchange;

    protected function setUp(): void
    {
        $this->con = TestDatabase::connection();
        $this->sales = new SaleService($this->con);
        $this->exchange = new ExchangeService($this->con);
    }

    public function testExchangeFailsWhenSaleNotActive(): void
    {
        $result = $this->exchange->exchange(1, 'jeans', 999999, 'Test Jean', 'L', 110, 110, 0, 'shop', null, 1);
        $this->assertFalse($result['success']);
        $this->assertSame('sale_not_active', $result['error']);
    }

    public function testExchangeMToLUpdatesStock(): void
    {
        TestDatabase::exec("UPDATE jeans SET quantity=5 WHERE jeans_name='Test Jean' AND size='M'");
        TestDatabase::exec("UPDATE jeans SET quantity=5 WHERE jeans_name='Test Jean' AND size='L'");

        $sale = $this->sales->processMultiSale(1, [
            ['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 100, 'cash' => 100, 'bank' => 0, 'quantity' => 1],
        ], 'shop');
        $this->assertGreaterThanOrEqual(1, $sale['success_count']);
        $salesId = (int) ($sale['sales_ids'][0]['sales_id'] ?? 0);
        $this->assertGreaterThan(0, $salesId);

        $mBefore = TestDatabase::scalar("SELECT quantity FROM jeans WHERE jeans_name='Test Jean' AND size='M'");
        $lBefore = TestDatabase::scalar("SELECT quantity FROM jeans WHERE jeans_name='Test Jean' AND size='L'");

        $ex = $this->exchange->exchange(1, 'jeans', $salesId, 'Test Jean', 'L', 110, 110, 0, 'shop', null, 1);
        $this->assertTrue($ex['success'] ?? false, $ex['error'] ?? 'exchange failed');

        $mAfter = TestDatabase::scalar("SELECT quantity FROM jeans WHERE jeans_name='Test Jean' AND size='M'");
        $lAfter = TestDatabase::scalar("SELECT quantity FROM jeans WHERE jeans_name='Test Jean' AND size='L'");
        $this->assertSame($mBefore + 1, $mAfter, 'original size stock restored');
        $this->assertSame($lBefore - 1, $lAfter, 'new size stock decremented');
    }
}
