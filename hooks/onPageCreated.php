<?php

use \Grav\Common\Grav;
use \Grav\Common\Page\Pages;
use \Grav\Common\Cache;
use util;

class onPageCreated implements Hook
{
    protected $grav;
    protected $cache;
    protected $url;

    public function __toString()
    {
        return 'onPageCreated';
    }

    public function init($url)
    {
        $this->url = $url[0];
        $this->grav = Grav::instance();
        $this->cache = new Cache($this->grav);
    }

    public function process()
    {
        $pages = new Pages($this->grav);
        $pages->init();
        $md5 = $this->cache->fetch('whopcmd5');
        if ($md5) {
            $this->grav['log']->debug($md5);
            $this->grav['log']->debug(serialize(array_keys($pages->getList())));
            if ($md5 != serialize(array_keys($pages->getList()))) {
                foreach ($this->url as $rUrl) {

                }

                $this->cache->save('whopcmd5', serialize(array_keys($pages->getList())));
            }
        } else {
            $this->grav['log']->debug('NoMatch');
            $this->cache->save('whopcmd5', serialize(array_keys($pages->getList())));
        }
    }

    private function findNewPages($originalSerialized, $newSerialized){
      $orignal = unserialize($originalSerialized);
      $new = unserialize($newSerialized);

      $diff = array_diff($original, $new);

      $newPages = array();

      foreach($diff as $route){
        $page = new Page();
        $newPages[] = $page->find($route);
      }

      return $newPages;
    }
}
