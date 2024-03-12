<?php

declare(strict_types=1);

namespace App\Entity;

use App\Reference\CouponDiscountType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxSystemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: TaxSystemRepository::class)]
class TaxSystem
{
    #[ORM\Id]
    #[ORM\Column(length: 26, unique: true)]
    private string $id;

    #[ORM\Column(length: 2, unique: true)]
    private string $countryCode;

    /** @psalm-var list<string> $taxNumberMasks */
    #[ORM\Column]
    private array $taxNumberMasks = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private float $amount = 0;

    public function __construct()
    {
        $this->id = Ulid::generate();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /** @return list<string> */
    public function getTaxNumberMasks(): array
    {
        return $this->taxNumberMasks;
    }

    /** @param list<string> $taxNumberMasks */
    public function setTaxNumberMasks(array $taxNumberMasks): void
    {
        $this->taxNumberMasks = $taxNumberMasks;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
