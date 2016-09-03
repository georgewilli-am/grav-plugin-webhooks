<?php

interface Hook
{
    public function __toString();
    public function init($url);
    public function process();
}
