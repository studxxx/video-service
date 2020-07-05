<?php
declare(strict_types=1);

namespace Api\Infrastructure\ReadModel\Video;

use Api\Model\Video\Entity\Video\Video;
use Api\ReadModel\Video\VideoReadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineVideoReadRepository implements VideoReadRepository
{
    /** @var EntityRepository */
    private $repository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Video::class);
        $this->em = $em;
    }

    public function allByAuthor(string $authorId): array
    {
        return $this->repository->createQueryBuilder('v')
            ->andWhere('v.author = :author')
            ->setParameter(':author', $authorId)
            ->orderBy('v.createDate', 'desc')
            ->getQuery()
            ->getResult();
    }

    public function find(string $authorId, string $id): ?Video
    {
        return $this->repository->findOneBy(['author' => $authorId, 'id' => $id]);
    }
}