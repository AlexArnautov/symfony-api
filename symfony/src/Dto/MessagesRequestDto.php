<?php

declare(strict_types=1);

namespace App\Dto;

use Carbon\CarbonImmutable;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class MessagesRequestDto
{
    public function __construct(
        private readonly CarbonImmutable $startDate,
        private readonly CarbonImmutable $endDate,
        private readonly array $ids,
    ) {
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint(
            'ids',
            new All([
                'constraints' => [
                    new NotBlank(),
                    new Type(['digit']),
                ],
            ])
        );

        $metadata->addPropertyConstraint(
            'startDate',
            new Sequentially([
                new NotBlank(),
                new DateTime(),
                new LessThan([
                    'propertyPath' => 'endDate'
                ]),
            ])
        );

        $metadata->addPropertyConstraint(
            'endDate',
            new Sequentially([
                new NotBlank(),
                new DateTime(),
                new GreaterThan([
                    'propertyPath' => 'startDate'
                ]),
            ])
        );
    }

    public function getEndDate(): CarbonImmutable
    {
        return $this->endDate;
    }

    public function getStartDate(): CarbonImmutable
    {
        return $this->startDate;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
