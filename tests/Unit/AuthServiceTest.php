<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AuthServiceTest extends TestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $con = TestDatabase::connection();
        $env = tests_load_env();
        $auth = new AuthService($con, ['token_ttl_days' => 7]);

        $result = $auth->login($env['user'], $env['pass'], $env['shop_slug']);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result['token']);
        $this->assertSame($env['user'], $result['user']['user_name']);
        $this->assertTrue($result['user']['is_master_admin']);
    }

    public function testLoginRejectsInvalidPassword(): void
    {
        $con = TestDatabase::connection();
        $env = tests_load_env();
        $auth = new AuthService($con, ['token_ttl_days' => 7]);

        $this->assertNull($auth->login($env['user'], 'wrong-password-xyz', $env['shop_slug']));
    }
}
