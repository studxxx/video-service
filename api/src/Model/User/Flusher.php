<?php
declare(strict_types=1);

namespace Api\Model\User;

interface Flusher
{
    public function flush(): void;
}
