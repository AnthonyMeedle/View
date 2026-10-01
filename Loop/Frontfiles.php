<?php

declare(strict_types=1);
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
use View\Service\FrontViewFinder;

/**
 * Class Commentaire
 * @package Commentaire\Loop
 * @author manuel raynaud <mraynaud@openstudio.fr>
 */
class Frontfiles extends BaseLoop implements ArraySearchLoopInterface
{
    public function __construct(private readonly FrontViewFinder $frontViewFinder)
    {
    }

    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions(): ArgumentCollection
    {
        return new ArgumentCollection(
            Argument::createAnyTypeArgument('templates-active')
        );
    }

    public function buildArray(): array
    {
        return $this->frontViewFinder->find();
    }

    public function parseResults(LoopResult $loopResult): LoopResult
    {
        foreach ($loopResult->getResultDataCollection() as $template) {
            $loopResultRow = new LoopResultRow($template);

            $loopResultRow
                ->set('NAME', $template['name'])
                ->set('FILE', $template['file'])
                ->set('RELATIVE_PATH', $template['relative_path'])
                ->set('ABSOLUTE_PATH', $template['absolute_path'])
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
