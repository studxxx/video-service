<?php
declare(strict_types=1);

namespace Api\Model\Video\UseCase\Video\Remove;

use Api\Model\Flusher;
use Api\Model\Video\Entity\Video\VideoId;
use Api\Model\Video\Entity\Video\VideoRepository;

class Handler
{
    /** @var VideoRepository */
    private $videos;
    /** @var Flusher */
    private $flusher;

    public function __construct(VideoRepository $videos, Flusher $flusher)
    {
        $this->videos = $videos;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $video = $this->videos->get(new VideoId($command->id));
        $video->remove();

        $this->videos->remove($video);

        $this->flusher->flush($video);
    }
}
