<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    public function testStockGetCategoriesReturnsEnabledSlugs(): void
    {
        $con = TestDatabase::connection();
        $cats = stock_get_categories($con);
        $this->assertNotEmpty($cats);
        $slugs = array_column($cats, 'slug');
        $this->assertContains('jeans', $slugs);
        $this->assertContains('shoes', $slugs);
    }

    public function testStockAllowedProductTypesMatchesCategories(): void
    {
        $con = TestDatabase::connection();
        $allowed = stock_allowed_product_types($con);
        $this->assertContains('jeans', $allowed);
        $this->assertTrue(stock_allowed_product_type('jeans', $con));
        $this->assertFalse(stock_allowed_product_type('invalid_cat', $con));
    }

    public function testStockProductTypeLabels(): void
    {
        $con = TestDatabase::connection();
        $labels = stock_product_type_labels($con);
        $this->assertArrayHasKey('jeans', $labels);
        $this->assertSame('Jeans', $labels['jeans']);
    }
}
