<?php

namespace Bluebadger\JasperPim\Model;

use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception;

class ValidationException extends Exception
{

    public function __construct(string $message)
    {
        $phrase = new Phrase($message);
        parent::__construct($phrase, 0, self::HTTP_BAD_REQUEST, [], '', null, null);
    }
}
