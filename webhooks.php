<?php

namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;

/**
 * Class WebhooksPlugin.
 */
class WebhooksPlugin extends Plugin
{
    private $HookClasses = array();
    protected $grav;

    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    /**
     * Initialize the plugin.
     */
    public function onPluginsInitialized()
    {
        $this->grav = Grav::instance();

        require_once __DIR__.'/util/util.php';
        require_once __DIR__.'/hooks/hook.php';
        
        if ($this->config->get('plugins.webhooks.indiviual_hooks')) {
            foreach ($this->config->get('plugins.webhooks.hooks') as $hook) {
                $hookName = key($hook);
                $hookURL = reset($hook);
                if ((@include_once(__DIR__.'/hooks/'.$hookName.'.php')) !== false) {
                    $hookObj = new $hookName();
                    $hookObj->init($HookURL);
                    $this->HookClasses[] = $hookObj;
                } else {
                    $this->grav['log']->warning('Failed to add '.$hookName.', could not find class.');
                }
            }
        } else {
            foreach ($this->config->get('plugins.webhooks.hooks') as $hook) {
                if (reset($hook) !== 'false') {
                    $hookName = key($hook);
                    if ((@include_once(__DIR__.'/hooks/'.$hookName.'.php')) !== false) {
                        $hookObj = new $hookName();
                        $hookObj->init($this->config->get('plugins.webhooks.webhook_url(s)'));
                        $this->HookClasses[] = $hookObj;
                    } else {
                        $this->grav['log']->warning('[WebHooks] Failed to add '.$hookName.', could not find class.');
                    }
                }
            }
        }

        $this->grav['log']->debug('[WebHooks] Loaded '.count($this->HookClasses).' web hook(s).');
        $this->grav['log']->debug('[WebHooks] '.implode(', ', array_values($this->HookClasses)));

        $this->enable([
            'onShutdown' => ['onShutdown', 1],
        ]);
    }

    public function onShutdown()
    {
        foreach ($this->HookClasses as $hook) {
            $hook->process();
        }
    }
}
