<?php
declare(strict_types=1);

namespace Api\Http;

use Api\Http\Validator\Errors;

class ValidatorException extends \LogicException
{
    /** @var Errors */
    private $errors;

    public function __construct(Errors $errors)
    {
        parent::__construct('Validation errors.');
        $this->errors = $errors;
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }
}