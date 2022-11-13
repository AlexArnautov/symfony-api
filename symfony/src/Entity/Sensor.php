<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SensorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: SensorRepository::class)]
class Sensor
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastSeenAt = null;

    #[ORM\Column(type: "string", nullable: true, columnDefinition: "ENUM('Internal Sensor Error', 'Traffic Light Error', 'Bandwidth Error')")]
    private ?string $lastError = null;

    #[ORM\Column(type: "string", nullable: true, columnDefinition: "ENUM('Red', 'Yellow', 'Green', 'Unknown State')")]
    private ?string $lastState = null;

    #[ORM\OneToMany(mappedBy: 'Sensor', targetEntity: Message::class, orphanRemoval: true)]
    #[Ignore]
    private Collection $messages;

    #[ORM\Column(type: "decimal", precision: 10, scale: 8, nullable: false)]
    private float $latitude;

    #[ORM\Column(type: "decimal", precision: 11, scale: 8, nullable: false)]
    private float $longitude;

    #[ORM\Column(type: "json", nullable: false)]
    private array $stats;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastSeenAt(): string
    {
        return $this->lastSeenAt->format('Y-m-d H:i:s');
    }

    public function setLastSeenAt(\DateTimeImmutable $lastSeenAt): self
    {
        $this->lastSeenAt = $lastSeenAt;

        return $this;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function setLastError(?string $lastError): self
    {
        $this->lastError = $lastError;

        return $this;
    }

    public function getLastState(): ?string
    {
        return $this->lastState;
    }

    public function setLastState(?string $lastState): void
    {
        $this->lastState = $lastState;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setSensor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSensor() === $this) {
                $message->setSensor(null);
            }
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint(
            'latitude',
            new Sequentially([
                new NotBlank(),
                new GreaterThanOrEqual(-90.0),
                new LessThanOrEqual(90.0),
            ])
        );

        $metadata->addPropertyConstraint(
            'longitude',
            new Sequentially([
                new NotBlank(),
                new GreaterThanOrEqual(-180.0),
                new LessThanOrEqual(180.0),
            ])
        );
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function setStats(array $stats): void
    {
        $this->stats = $stats;
    }
}
