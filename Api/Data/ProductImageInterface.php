<?php

namespace Bluebadger\JasperPim\Api\Data;

interface ProductImageInterface
{
    /**
     * @return string
     */
    public function getUrl();
    /**
     * @param string
     * @return $this
     */
    public function setUrl($url);

    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getAlternateText();

    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[] $alternate_text
     * @return $this
     */
    public function setAlternateText($alternate_text);

    /**
     * @return boolean
     */
    public function getHidden();

    /**
     * @param boolean $hidden
     * @return $this
     */
    public function setHidden($hidden);

    /**
     * @return string[]
     */
    public function getRoles();
    /**
     * @param string[] $roles
     * @return $this
     */
    public function setRoles($roles);
}
