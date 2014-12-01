<?php
/**
 * Created by PhpStorm.
 * User: chevrier_meedle
 * Date: 22/05/2014
 * Time: 18:46
 */

namespace View\Listener;
use View\Model\ViewQuery;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerListener implements EventSubscriberInterface
{

    public function controllerListener(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
		
        //var_dump($request->attributes);
        //var_dump($request);
	//	echo $request->query->get('view');
	//	echo $request->query->get($request->query->get('view').'_id');
		
		if($request->query->get('product_id')!=0){
			$view = ViewQuery::Create()->findSSID('product', $request->query->get('product_id'));
			//var_dump($view);

			if($view !== NULL && $view->getView())
				$request->query->set('view', $view->getView());
		}elseif($request->query->get('category_id')!=0){
			$view = ViewQuery::Create()->findSSID('category', $request->query->get('category_id'));
			//var_dump($view);

			if($view !== NULL && $view->getView())
				$request->query->set('view', $view->getView());
		}elseif($request->query->get('content_id')!=0){
			$view = ViewQuery::Create()->findSSID('content', $request->query->get('content_id'));
			//var_dump($view);

			if($view !== NULL && $view->getView())
				$request->query->set('view', $view->getView());
		}elseif($request->query->get('folder_id')!=0){
			$view = ViewQuery::Create()->findSSID('folder', $request->query->get('folder_id'));
			//var_dump($view);

			if($view !== NULL && $view->getView())
				$request->query->set('view', $view->getView());
		}	
//		echo $request->query->get('view');
		//	$request->attributes->set("view", "carres_rea");
		//	$request->attributes->set("_view", "carres_rea");
		
			

			
        //var_dump($request->query->get('category_id'));
        // $request->attributes->set("_view", "category");
       // var_dump($request->attributes); exit;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ["controllerListener", 128]
        ];
    }
}