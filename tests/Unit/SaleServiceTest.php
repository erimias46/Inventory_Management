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

    public function testCashMethodMapsToShop(): void
    {
        $result = $this->sales->processMultiSale(1, [
            ['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 10, 'cash' => 10, 'bank' => 0, 'quantity' => 1],
        ], 'cash');
        $this->assertGreaterThanOrEqual(1, $result['success_count']);
        $id = (int) ($result['sales_ids'][0]['sales_id'] ?? 0);
        $this->assertGreaterThan(0, $id);
        $method = TestDatabase::stringScalar("SELECT method FROM sales WHERE sales_id=$id");
        $this->assertSame('shop', $method);
    }

    public function testInvalidTypeRejected(): void
    {
        $result = $this->sales->processMultiSale(1, [
            ['type' => 'invalid_cat', 'name' => 'X', 'size' => 'M', 'price' => 1, 'cash' => 1, 'bank' => 0, 'quantity' => 1],
        ], 'shop');
        $this->assertSame(0, $result['success_count']);
        $this->assertSame('invalid_type', $result['errors'][0]['error'] ?? '');
    }

    public function testRefundRestoresStock(): void
    {
        TestDatabase::exec("UPDATE shoes SET quantity=4 WHERE shoes_name='Test Shoe' AND size='40'");
        $result = $this->sales->processMultiSale(1, [
            ['type' => 'shoes', 'name' => 'Test Shoe', 'size' => '40', 'price' => 200, 'cash' => 200, 'bank' => 0, 'quantity' => 1],
        ], 'shop');
        $salesId = (int) ($result['sales_ids'][0]['sales_id'] ?? 0);
        $this->assertGreaterThan(0, $salesId);
        $qtyAfterSale = TestDatabase::scalar("SELECT quantity FROM shoes WHERE shoes_name='Test Shoe' AND size='40'");
        $this->assertSame(3.0, (float) $qtyAfterSale);

        $this->assertTrue($this->sales->refund('shoes', $salesId, 1));
        $status = TestDatabase::stringScalar("SELECT status FROM shoes_sales WHERE sales_id=$salesId");
        $this->assertSame('refunded', $status);
        $qtyAfterRefund = TestDatabase::scalar("SELECT quantity FROM shoes WHERE shoes_name='Test Shoe' AND size='40'");
        $this->assertSame(4.0, (float) $qtyAfterRefund);
    }
}
