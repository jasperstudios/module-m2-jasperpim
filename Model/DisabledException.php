<?php

namespace Bluebadger\JasperPim\Model;

use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception;

class DisabledException extends Exception
{

    public function __construct()
    {
        $phrase = new Phrase('JasperPIM API is disabled');
        parent::__construct($phrase, 0, 404, [], '', null, null);
    }
}
