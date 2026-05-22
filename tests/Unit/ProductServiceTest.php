<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ProductServiceTest extends TestCase
{
    public function testCategoriesAndTypes(): void
    {
        $con = TestDatabase::connection();
        $env = tests_load_env();
        $svc = new ProductService($con, 'http://127.0.0.1:8888/stock');

        $categories = $svc->categories();
        $this->assertNotEmpty($categories);
        $this->assertArrayHasKey('slug', $categories[0]);

        $types = $svc->types();
        $this->assertNotEmpty($types);
        $keys = array_column($types, 'key');
        $this->assertContains('jeans', $keys);
    }
}
