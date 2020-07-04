<?php
declare(strict_types=1);

namespace Api\Model\Video\UseCase\Video\Create;

use Psr\Http\Message\UploadedFileInterface;

class Command
{
    /**
     * @var string
     */
    public $author;
    /**
     * @var UploadedFileInterface
     */
    public $file;
}
