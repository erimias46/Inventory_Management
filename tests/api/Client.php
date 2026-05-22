<?php

declare(strict_types=1);

final class ApiTestClient
{
    private string $base;
    private ?string $token = null;

    public function __construct(string $baseUrl)
    {
        $this->base = rtrim($baseUrl, '/');
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /** @return array{code: int, raw: string, json: array<string, mixed>} */
    public function request(string $method, string $path, ?array $body = null, bool $auth = true): array
    {
        $url = $this->base . $path;
        $headers = ['Content-Type: application/json', 'Accept: application/json'];
        if ($auth && $this->token) {
            $headers[] = "Authorization: Bearer {$this->token}";
            $headers[] = "X-Api-Token: {$this->token}";
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body !== null ? json_encode($body) : null,
            CURLOPT_TIMEOUT => 30,
        ]);
        $raw = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($raw ?: '{}', true);
        if (!is_array($json)) {
            $json = [];
        }

        return ['code' => $code, 'raw' => (string) $raw, 'json' => $json];
    }
}

final class ApiTestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    /** @var list<string> */
    private array $failures = [];

    public function assert(bool $condition, string $name, string $detail = ''): void
    {
        if ($condition) {
            $this->passed++;
            echo "  ✓ $name\n";
            return;
        }
        $this->failed++;
        $msg = $detail !== '' ? "$name — $detail" : $name;
        $this->failures[] = $msg;
        echo "  ✗ $msg\n";
    }

    public function section(string $title): void
    {
        echo "\n=== $title ===\n";
    }

    public function summary(): int
    {
        echo "\n----------------------------------------\n";
        echo "Passed: {$this->passed}, Failed: {$this->failed}\n";
        if ($this->failures !== []) {
            echo "Failures:\n";
            foreach ($this->failures as $f) {
                echo "  - $f\n";
            }
        }
        return $this->failed > 0 ? 1 : 0;
    }
}
