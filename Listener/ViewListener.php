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

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Action\BaseAction;
use Thelia\Model\CategoryQuery;
use Thelia\Model\ContentQuery;
use Thelia\Model\FolderQuery;
use Thelia\Model\ProductQuery;
use View\Event\FindViewEvent;
use View\Event\ViewEvent;
use View\Model\View;
use View\Model\ViewQuery;

/**
 * Class View
 * @package View\Listener
 */
class ViewListener extends BaseAction implements EventSubscriberInterface
{
    public function create(ViewEvent $event)
    {
        if (null === $view = ViewQuery::create()->filterBySourceId($event->getSourceId())->findOneBySource($event->getSource())) {
            $view = new View();
        }

        if ($event->hasDefinedViews()) {
            $view
                ->setView($event->getViewName())
                ->setSource($event->getSource())
                ->setSourceId($event->getSourceId())
                ->setSubtreeView($event->getSubtreeView())
                ->setChildrenView($event->getChildrenView())
                ->save();
        } else {
            $view->delete();
        }
    }

    public function find(FindViewEvent $event)
    {
        $objectType = $event->getObjectType();
        $objectId   = $event->getObjectId();

        // Try to find a direct match. A view is defined for the object.
        if (null !== $viewObj = ViewQuery::create()
                ->filterBySourceId($objectId)
                ->findOneBySource($objectType)
        ) {
            $viewName = $viewObj->getView();

            if (! empty($viewName)) {
                $event->setView($viewName)->setViewObject($viewObj);
                return;
            }
        }

        $foundView = $sourceView = null;

        if ($objectType == 'category') {
            $foundView = $this->searchInParents($objectId, $objectType, CategoryQuery::create(), false, $sourceView);
        } elseif ($objectType == 'folder') {
            $foundView = $this->searchInParents($objectId, $objectType, FolderQuery::create(), false, $sourceView);
        } elseif ($objectType == 'product') {
            if (null !== $product = ProductQuery::create()->findPk($objectId)) {
                $foundView = $this->searchInParents($product->getDefaultCategoryId(), 'category', CategoryQuery::create(), true, $sourceView);
            }
        } elseif ($objectType == 'content') {
            if (null !== $content = ContentQuery::create()->findPk($objectId)) {
                $foundView = $this->searchInParents($content->getDefaultFolderId(), 'folder', FolderQuery::create(), true, $sourceView);
            }
        }

        $event->setView($foundView)->setViewObject($sourceView);
    }

    /**
     * Try to find the custom template in the object parent.
     *
     * @param $objectId int the object id
     * @param $objectType string the object type
     * @param $objectQuery ModelCriteria the object query to use
     * @param $forLeaf bool seach for a leaf (product, content) or node (category, folder)
     * @param $sourceView ModelCriteria the model of the found View, returned ti the caller.
     *
     * @return bool
     */
    protected function searchInParents($objectId, $objectType, $objectQuery, $forLeaf, &$sourceView)
    {
        // For a folder or a category, search template in the object's parent instead of getting object's one.
        // To do this, we will ignore the first loop iteration.
        $ignoreIteration = ! $forLeaf;

        while (null !== $object = $objectQuery->findPk($objectId)) {
            if (! $ignoreIteration) {
                if (null !== $viewObj = ViewQuery::create()->filterBySourceId($object->getId())->findOneBySource($objectType)) {
                    $viewName = $forLeaf ? $viewObj->getChildrenView() : $viewObj->getSubtreeView();

                    if (!empty($viewName)) {
                        $sourceView = $viewObj;

                        return $viewName;
                    }
                }
            }

            $ignoreIteration = false;

            // Not found. Try to find the view in the parent object.
            $objectId = $object->getParent();
        }

        return false;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'view.create' => array('create', 128),
            'view.find'   => array('find', 128)
        );
    }
}
