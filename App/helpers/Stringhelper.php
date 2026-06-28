<?php
namespace App\helpers;

class StringHelper {
    public static function slug(string $text): string {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }

    public static function uniqueSlug(string $text, callable $existsCheck): string {
        $slug = self::slug($text);
        $original = $slug;
        $i = 1;
        while ($existsCheck($slug)) {
            $slug = $original . '-' . $i;
            $i++;
        }
        return $slug;
    }

    public static function formatRupiah(float $amount): string {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}