<?php
declare(strict_types=1);

namespace Api\Infrastructure\Model\Video\Entity;

use Api\Model\EntityNotFoundException;
use Api\Model\Video\Entity\Video\Video;
use Api\Model\Video\Entity\Video\VideoId;
use Api\Model\Video\Entity\Video\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineVideoRepository implements VideoRepository
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

    public function get(VideoId $id): Video
    {
        /** @var Video $video */
        if (!$video = $this->repository->find($id->getId())) {
            throw new EntityNotFoundException('Video is not found.');
        }
        return $video;
    }

    public function add(Video $video): void
    {
        $this->em->persist($video);
    }

    public function remove(Video $video): void
    {
        $this->em->remove($video);
    }
}