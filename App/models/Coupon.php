<?php
namespace App\models;

class Coupon extends BaseModel {
    protected $table = 'coupons';

    public function findValidByCode(string $code): ?array {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE code = ? AND expired_at >= CURDATE()");
        $stmt->execute([$code]);
        return $stmt->fetch() ?: null;
    }
}