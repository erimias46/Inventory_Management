<?php

declare(strict_types=1);

final class ShopController
{
    public function index(): void
    {
        $master = stock_master_connect();
        if (!$master) {
            ApiResponse::success([]);
        }

        $res   = mysqli_query($master, "SELECT id, name, slug FROM shops WHERE active=1 ORDER BY name ASC");
        $shops = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $shops[] = [
                    'id'   => (int) $row['id'],
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                ];
            }
        }
        mysqli_close($master);
        ApiResponse::success($shops);
    }
}
