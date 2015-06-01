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

namespace View\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use View\Event\FindViewEvent;

/**
 * Class FrontView
 * @package View\Loop
 */
class FrontView extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createAnyTypeArgument('source'),
            Argument::createIntTypeArgument('source_id')
        );
    }

    public function buildArray()
    {
        $findEvent = new FindViewEvent($this->getSourceId(), $this->getSource());

        $this->dispatcher->dispatch('view.find', $findEvent);

        return $findEvent->hasView() ? [ [
            'name' => $findEvent->getView(),
            'id'   => $findEvent->getViewObject()->getId()
        ] ] : [];
    }


    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $view) {
            $loopResultRow = new LoopResultRow($view);

            $loopResultRow
                ->set('FRONT_VIEW', $view['name'])
                ->set('VIEW_ID', $view['id'])
                ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
