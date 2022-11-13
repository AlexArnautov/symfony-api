<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Sensor $sensor = null;

    #[ORM\Column]
    private int $sensorId;

    #[ORM\Column]
    private DateTimeImmutable $timeStamp;

    #[ORM\Column(type: "string", nullable: false, columnDefinition: "ENUM('Traffic Light State', 'Error')")]
    private string $type;

    #[ORM\Column(type: "string", nullable: true, columnDefinition: "ENUM('Internal Sensor Error', 'Traffic Light Error', 'Bandwidth Error')")]
    private ?string $errorContent = null;

    #[ORM\Column(type: "string", nullable: true, columnDefinition: "ENUM('Red', 'Yellow', 'Green', 'Unknown State')")]
    private ?string $stateContent = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensor(): ?Sensor
    {
        return $this->sensor;
    }

    public function setSensor(?Sensor $sensor): self
    {
        $this->sensor = $sensor;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getErrorContent(): ?string
    {
        return $this->errorContent;
    }

    public function setErrorContent(?string $errorContent): void
    {
        $this->errorContent = $errorContent;
    }

    public function getStateContent(): ?string
    {
        return $this->stateContent;
    }

    public function setStateContent(?string $stateContent): void
    {
        $this->stateContent = $stateContent;
    }

    public function getSensorId(): int
    {
        return $this->sensorId;
    }

    public function setSensorId(int $sensorId): void
    {
        $this->sensorId = $sensorId;
    }

    public function getTimeStamp(): DateTimeImmutable
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(DateTimeImmutable $timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }
}
