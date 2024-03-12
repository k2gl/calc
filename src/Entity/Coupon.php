<?php

declare(strict_types=1);

namespace App\Entity;

use App\Reference\CouponDiscountType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\Column(length: 26, unique: true)]
    private string $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $code;

    #[ORM\Column]
    private CouponDiscountType $discountType = CouponDiscountType::FIXED;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private float $discountAmount = 0;

    public function __construct()
    {
        $this->id = Ulid::generate();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getDiscountType(): CouponDiscountType
    {
        return $this->discountType;
    }

    public function setDiscountType(CouponDiscountType $discountType): void
    {
        $this->discountType = $discountType;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(float $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getFixedDiscountAmount(): ?float
    {
        if (!$this->getDiscountType()->is(CouponDiscountType::FIXED)) {
            return null;
        }

        return $this->getDiscountAmount();
    }

    public function getPercentageDiscountAmount(): ?float
    {
        if (!$this->getDiscountType()->is(CouponDiscountType::PERCENTAGE)) {
            return null;
        }

        return $this->getDiscountAmount();
    }
}
