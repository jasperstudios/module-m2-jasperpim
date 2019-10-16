<?php

namespace Bluebadger\JasperPim\Model;

abstract class AbstractRepository
{
    public function __construct(\Bluebadger\JasperPim\Helper\Config $configHelper)
    {
        if (!$configHelper->getEnable()) {
            throw new DisabledException();
        }
    }
}
