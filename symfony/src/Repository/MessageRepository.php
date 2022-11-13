<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findBetweenDates(array $ids, CarbonImmutable $start, CarbonImmutable $end): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.timeStamp BETWEEN :start AND :end')
            ->andWhere('m.sensorId IN (:ids)')
            ->setParameter('ids', $ids)
            ->setParameter('start', $start->toDateTimeString())
            ->setParameter('end', $end->toDateTimeString())
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
