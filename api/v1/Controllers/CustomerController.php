<?php

declare(strict_types=1);

final class CustomerController
{
    public function __construct(private UserService $users, private AuthService $auth) {}

    public function names(): void
    {
        ApiResponse::success($this->users->listCustomerNames());
    }

    public function manage(array $user): void
    {
        $this->auth->requireModule($user, 'custview');
        ApiResponse::success($this->users->listCustomers());
    }

    public function banks(): void
    {
        ApiResponse::success($this->users->listBanks());
    }
}
