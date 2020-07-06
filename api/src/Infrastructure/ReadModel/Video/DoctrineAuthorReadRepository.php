<?php
declare(strict_types=1);

namespace Api\Infrastructure\ReadModel\Video;

use Api\Model\Video\Entity\Author\Author;
use Api\ReadModel\Video\AuthorReadRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineAuthorReadRepository implements AuthorReadRepository
{
    /** @var \Doctrine\ORM\EntityRepository */
    private $repository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Author::class);
        $this->em = $em;
    }

    public function find(string $id): ?Author
    {
        return $this->repository->find($id);
    }
}