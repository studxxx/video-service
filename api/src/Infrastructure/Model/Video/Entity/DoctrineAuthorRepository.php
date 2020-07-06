<?php
declare(strict_types=1);

namespace Api\Infrastructure\Model\Video\Entity;

use Api\Model\EntityNotFoundException;
use Api\Model\Video\Entity\Author\Author;
use Api\Model\Video\Entity\Author\AuthorId;
use Api\Model\Video\Entity\Author\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineAuthorRepository implements AuthorRepository
{
    /** @var EntityRepository */
    private $repository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Author::class);
        $this->em = $em;
    }

    public function hasById(AuthorId $id): bool
    {
        return $this->repository->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.id = :id')
            ->setParameter(':id', $id->getId())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function get(AuthorId $id): Author
    {
        /** @var Author $author */
        if (!$author = $this->repository->find($id->getId())) {
            throw new EntityNotFoundException('Author is not found.');
        }

        return $author;
    }

    public function add(Author $author): void
    {
        $this->em->persist($author);
    }
}
