<?php
namespace App\helpers;

class CartHelper {
    private const KEY = 'cart';

    public static function all(): array {
        return $_SESSION[self::KEY] ?? [];
    }

    public static function add(int $productId): void {
        $cart = self::all();
        if (!in_array($productId, $cart)) {
            $cart[] = $productId;
        }
        $_SESSION[self::KEY] = $cart;
    }

    public static function remove(int $productId): void {
        $cart = self::all();
        $_SESSION[self::KEY] = array_values(array_diff($cart, [$productId]));
    }

    public static function clear(): void {
        unset($_SESSION[self::KEY]);
    }

    public static function count(): int {
        return count(self::all());
    }

    public static function has(int $productId): bool {
        return in_array($productId, self::all());
    }
}