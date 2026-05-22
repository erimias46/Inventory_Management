<?php

declare(strict_types=1);

final class UserController
{
    public function __construct(
        private UserService $users,
        private AuthService $auth
    ) {}

    public function index(array $user): void
    {
        $this->auth->requireModule($user, 'user');
        ApiResponse::success($this->users->listUsers());
    }

    public function show(array $user, int $id): void
    {
        $this->auth->requireModule($user, 'user');
        $u = $this->users->getUser($id, true);
        if (!$u) {
            ApiResponse::error('not_found', 'User not found', 404);
        }
        ApiResponse::success($u);
    }

    public function create(array $user): void
    {
        $this->auth->requireModule($user, 'user');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $id = $this->users->createUser(
            (string) ($body['user_name'] ?? ''),
            (string) ($body['password'] ?? ''),
            (string) ($body['privilege'] ?? 'user'),
            $body['modules'] ?? []
        );
        ApiResponse::success(['id' => $id, 'user' => $this->users->getUser($id)], 201);
    }

    public function update(array $user, int $id): void
    {
        $this->auth->requireModule($user, 'user');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        if (!$this->users->updateUser($id, $body)) {
            ApiResponse::error('failed', 'Could not update user', 400);
        }
        ApiResponse::success($this->users->getUser($id, true));
    }

    public function destroy(array $user, int $id): void
    {
        $this->auth->requireModule($user, 'user');
        if (!$this->users->deleteUser($id)) {
            ApiResponse::error('failed', 'Could not delete user', 400);
        }
        ApiResponse::success(['deleted' => true]);
    }

    public function updateProfile(array $user): void
    {
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        if (!$this->users->updateProfile(
            $user['id'],
            (string) ($body['user_name'] ?? $user['user_name']),
            (string) ($body['password'] ?? '')
        )) {
            ApiResponse::error('failed', 'Could not update profile', 400);
        }
        ApiResponse::success(['user' => $this->users->getUser($user['id'])]);
    }

    public function moduleKeys(array $user): void
    {
        $this->auth->requireModule($user, 'user');
        ApiResponse::success($this->users->moduleDefinitions());
    }

    public function constants(array $user): void
    {
        $this->auth->requireModule($user, 'constant');
        ApiResponse::success($this->users->listConstants());
    }
}
