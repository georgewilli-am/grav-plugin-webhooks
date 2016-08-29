<?php

use \Grav\Common\Grav;

interface Hook
{
    protected $url;

    public function getName();
    public function init($url);
    public function process();
}
