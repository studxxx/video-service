<?php
declare(strict_types=1);

namespace Api\Infrastructure\Model\OAuth\Entity;

use Api\Model\OAuth\Entity\RefreshTokenEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /** @var EntityRepository */
    private $repository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(RefreshTokenEntity::class);
        $this->em = $em;
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        if (!$this->exists($refreshTokenEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
        $this->em->persist($refreshTokenEntity);
        $this->em->flush();
    }

    public function revokeRefreshToken($tokenId): void
    {
        if ($token = $this->repository->find($tokenId)) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        return !$this->exists($tokenId);
    }

    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        return new RefreshTokenEntity();
    }

    private function exists($id): bool
    {
        return $this->repository->createQueryBuilder('t')
            ->select('COUNT(t.identifier)')
            ->andWhere('t.identifier = :identifier')
            ->setParameter(':identifier', $id)
            ->getQuery()->getSingleScalarResult() > 0;
    }
}
