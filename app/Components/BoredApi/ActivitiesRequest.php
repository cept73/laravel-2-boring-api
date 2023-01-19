<?php /** @noinspection PhpUnused */

namespace App\Components\BoredApi;

use ReflectionClass;

class ActivitiesRequest
{
    public const DEFAULT_ON_PAGE_LIMIT = 10;

    private static ?array $propertiesNames = null;

    public function __construct(
        private ?int $onPage        = null,
        private ?int $participant   = null,
        private ?float $price       = null,
        private ?string $type       = null,
    )
    {
    }

    public function propertiesNames(): array
    {
        if (self::$propertiesNames !== null) {
            return self::$propertiesNames;
        }

        $propertiesNames = [];
        $classProperties = (new ReflectionClass($this))->getProperties();
        foreach ($classProperties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $propertiesNames[] = $property->getName();
        }
        self::$propertiesNames = $propertiesNames;

        return $propertiesNames;
    }

    public function get($propertyName)
    {
        if (in_array($propertyName, $this->propertiesNames())) {
            return $this->$propertyName();
        }

        return null;
    }

    public function populateFromArray($array): self
    {
        $propertiesNames = $this->propertiesNames();

        foreach ($propertiesNames as $key) {
            if (($value = $array[$key] ?? null) !== null) {
                $method = 'set' . ucfirst($key);
                print " $method = $value; ";
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function asArray(): array
    {
        $array = [];
        foreach ($this->propertiesNames() as $property) {
            $array[$property] = $this->$property();
        }
        return $array;
    }

    public function setParticipant(int $participant): self
    {
        $this->participant = $participant;
        return $this;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setOnPage(int $onPage): self
    {
        $this->onPage = $onPage;
        return $this;
    }

    /**
     * @return int|null
     */
    public function participant(): ?int
    {
        return $this->participant;
    }

    /**
     * @return float|null
     */
    public function price(): ?float
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function type(): ?string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function onPage(): int
    {
        return $this->onPage ?: self::DEFAULT_ON_PAGE_LIMIT;
    }
}