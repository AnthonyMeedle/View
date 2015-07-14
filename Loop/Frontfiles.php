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

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Core\Template\TemplateHelper;
use Thelia\Core\Template\TheliaTemplateHelper;
use Thelia\Type;

/**
 * Class Commentaire
 * @package Commentaire\Loop
 * @author manuel raynaud <mraynaud@openstudio.fr>
 */
class Frontfiles extends BaseLoop implements ArraySearchLoopInterface
{
    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createAnyTypeArgument('templates-active')
        );
    }

    public function buildArray()
    {
        try {
            /** @var TheliaTemplateHelper $templateHelper */
            $templateHelper = $this->container->get('thelia.template_helper');
        } catch (\Exception $ex) {
            $templateHelper = TemplateHelper::getInstance();
        }

        $frontTemplatePath = $templateHelper->getActiveFrontTemplate()->getAbsolutePath();

        $list = [];

        $finder = Finder::create()
            ->files()
            ->in($frontTemplatePath)
            // Ignore bower and node directories
            ->notPath('/bower_components/')
            ->notPath('/node_modules/')
            // Ignore VCS related directories
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->sortByName()
            ->name("*.html");

        foreach ($finder as $file) {
            $list[] = $file;
        }

        return $list;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var SplFileInfo $template */
        foreach ($loopResult->getResultDataCollection() as $template) {
            $loopResultRow = new LoopResultRow($template);

            $loopResultRow
                ->set("NAME", str_replace('.html', '', $template->getFilename()))
                ->set("FILE", $template->getFilename())
                ->set("RELATIVE_PATH", $template->getRelativePath())
                ->set("ABSOLUTE_PATH", $template->getPath())
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
