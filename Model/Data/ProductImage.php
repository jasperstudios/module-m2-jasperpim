<?php

namespace Bluebadger\JasperPim\Model\Data;

class ProductImage implements \Bluebadger\JasperPim\Api\Data\ProductImageInterface
{
    private $url = '';
    private $alternateText = [];
    private $hidden = false;
    private $roles = [
        "image", "small_image", "thumbnail", "swatch_image"
    ];

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    public function getAlternateText()
    {
        return $this->alternateText;
    }
    public function setAlternateText($alternate_text)
    {
        $this->alternateText = $alternate_text;
        return $this;
    }
    public function getHidden()
    {
        return $this->hidden;
    }
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }
    public function getRoles()
    {
        return $this->roles;
    }
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }
}
