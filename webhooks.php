<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class WebhooksPlugin
 * @package Grav\Plugin
 */
class WebhooksPlugin extends Plugin
{
    private HookClasses = array();

    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        if($this->config->get('plugins.webhooks.indiviual_hooks')){
          for($this->config->get('plugins.webhooks.hooks' as $hook => $url)){
            if($url != null || $url != false){
              require(__DIR__ . '/hooks/' $key . '.php');
              $hookObj = new $hook();
              $hookObj->init($url);
              HookClasses[] = $hookObj;
            }
          }
        }else{
          for($this->config->get('plugins.webhooks.hooks') as $hook => $url){
            if($url != false){
              require(__DIR__ . '/hooks/' $key . '.php');
              $hookObj = new $hook();
              $hookObj->init($this->config->get('plugins.webhooks.webhook_url(s)'));
              HookClasses[] = $hookObj;
            }
          }
        }

        $this->enable([
            'onShutdown' => ['onShutdown', 0]
        ]);
    }

    public function onShutdown(){
      for($HookClasses as $hook){
        $hook.process();
      }
    }

}
