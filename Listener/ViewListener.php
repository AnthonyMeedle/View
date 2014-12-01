<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace View\Listener;
use View\Event\ViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Action\BaseAction;
use View\Model\ViewQuery;
//use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Class CommentaireListener
 * @package Commentaire\Listener
 * @author manuel raynaud <mraynaud@openstudio.fr>
 */
class ViewListener extends BaseAction implements EventSubscriberInterface
{

    public function create(ViewEvent $event)
    {
    //    $view = new \View\Model\View();
			//$event->getId()	
	//	$view = ViewQuery::Create()->findPk(3);
	//	$view->findSSID($event->getSourceId());
//		$view = ViewQuery::Create()->filterBySource($event->getSource())->filterBySourceId($event->getSourceId());
//	array('source'=>$event->getSource(),'source_id'=>$event->getSourceId())
//		$view = ViewQuery::Create()->findPk(2);
	//	$view = ViewQuery::Create()->findSSId($event->getSource(), $event->getSourceId());
		
		
		$view = ViewQuery::Create()->findSSID($event->getSource(), $event->getSourceId());
		if($view === NULL)$view = new \View\Model\View();

		
        $view
            ->setView($event->getView())
            ->setSource($event->getSource())
            ->setSourceId($event->getSourceId())
            ->save();
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
        return array(
            'view.create' => array('create', 128)
        );
    }
}