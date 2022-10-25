<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 27/08/2019
 * Time: 13:41
 */

namespace View\Hook;


use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class HookManager extends BaseHook
{
    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        $event->add(
            $this->render("ViewConfiguration.html")
        );
    }

    public function onEditModuleTab(HookRenderEvent $event): void
    {
        $view = $event->getArgument('view');
        $event->add(
            $this->render("View-".$view.".html")
        );
    }
}
