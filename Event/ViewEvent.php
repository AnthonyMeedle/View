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

/**
 * Class ViewEvent
 * @package View\Event
 */
class ViewEvent extends ActionEvent
{

    /** @var  string */
    protected $view;
    /** @var  string */
    protected $source;
    /** @var  int */
    protected $source_id;
    /** @var  string */
    protected $childrenView = '';
    /** @var  string */
    protected $subtreeView = '';

    public function __construct($view, $source, $source_id)
    {
        $this->view = $view;
        $this->source = $source;
        $this->source_id = $source_id;
    }

    public function hasDefinedViews()
    {
        return ! (empty($this->view) && empty($this->childrenView) && empty($this->subtreeView));
    }

    /**
     * @param string $view the view name
     * @return $this
     */
    public function setViewName($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewName()
    {
        return $this->view;
    }


    /**
     * @param string $source the source object, one of product, category, content, folder
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param int $source_id
     * @return $this
     */
    public function setSourceId($source_id)
    {
        $this->source_id = $source_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getSourceId()
    {
        return $this->source_id;
    }

    /**
     * @return string
     */
    public function getChildrenView()
    {
        return $this->childrenView;
    }

    /**
     * @param string $childrenView
     * @return $this
     */
    public function setChildrenView($childrenView)
    {
        $this->childrenView = $childrenView;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubtreeView()
    {
        return $this->subtreeView;
    }

    /**
     * @param string $subtreeView
     * @return $this
     */
    public function setSubtreeView($subtreeView)
    {
        $this->subtreeView = $subtreeView;
        return $this;
    }
}
