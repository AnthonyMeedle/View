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

namespace View\Event;

use Thelia\Core\Event\ActionEvent;
use Thelia\Core\HttpFoundation\Request;
use View\Model\View;

/**
 * Class FindViewEvent
 * @package View\Event
 */
class FindViewEvent extends ActionEvent
{
    /** @var  int */
    protected $objectId;
    /** @var  int */
    protected $objectType;
    /** @var  string */
    protected $view;
    /** @var  View */
    protected $viewObject;

    public function __construct($objectId, $objectType)
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
    }

    public function hasView()
    {
        return ! empty($this->view);
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @return int
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * @return View
     */
    public function getViewObject()
    {
        return $this->viewObject;
    }

    /**
     * @param View $viewObject
     * @return $this
     */
    public function setViewObject($viewObject)
    {
        $this->viewObject = $viewObject;
        return $this;
    }
}
