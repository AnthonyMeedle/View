<?php
/**
 * Created by PhpStorm.
 * User: chevrier_meedle
 * Date: 22/05/2014
 * Time: 18:46
 */

namespace View\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use View\Event\FindViewEvent;

class ControllerListener implements EventSubscriberInterface
{

    public function controllerListener(FilterControllerEvent $event)
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
            if ($currentView == $objectType && (null !== $objectId = $request->query->get($parameter))) {
                $findEvent = new FindViewEvent($objectId, $objectType);

                $event->getDispatcher()->dispatch('view.find', $findEvent);

                if ($findEvent->hasView()) {
                    $event->getRequest()->query->set('view', $findEvent->getView());
                }

                return;
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ["controllerListener", 128]
        ];
    }
}
