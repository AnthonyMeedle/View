<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: chevrier_meedle
 * Date: 22/05/2014
 * Time: 18:46
 */

namespace View\Listener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use View\Event\FindViewEvent;

class ControllerListener implements EventSubscriberInterface
{
    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    public function controllerListener(ControllerEvent $event): void
    {
        static $possibleMatches = [
            'product_id'  => 'product',
            'category_id' => 'category',
            'content_id'  => 'content',
            'folder_id'   => 'folder'
        ];

        $request = $event->getRequest();

        $currentView = $event->getRequest()->attributes->get('_view');
        // Try to find a direct match. A view is defined for the object.
        foreach ($possibleMatches as $parameter => $objectType) {
            // Search for a view when the parameter is present in the request, and
            // the current view is the default one (fix for https://github.com/AnthonyMeedle/thelia-modules-View/issues/6)
            $objectId = $request->attributes->get($parameter) ?? $request->query->get($parameter);

            if ($currentView === $objectType && null !== $objectId && (int) $objectId > 0) {

                $findEvent = new FindViewEvent((int) $objectId, $objectType);
                $this->dispatcher->dispatch($findEvent, FindViewEvent::FIND);

                if ($findEvent->hasView()) {
                    // Thelia 3's ViewRenderer reads the routed `_view` attribute.
                    $request->attributes->set('_view', $findEvent->getView());
                }

                return;
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ["controllerListener", 128]
        ];
    }
}
