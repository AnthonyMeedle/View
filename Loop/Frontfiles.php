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
use View\Model\ViewQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;

use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type\BooleanOrBothType;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;

use Thelia\Core\Template\Loop\Config;



use Thelia\Type;
use Thelia\Core\Template\TemplateHelper;
use Thelia\Core\Template\TemplateDefinition;



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
            Argument::createAnyTypeArgument('template-active')
        );
    }

    public function buildArray()
    {
	/*	$templateType = TemplateDefinition::FRONT_OFFICE;

        return TemplateHelper::getInstance()->getList($templateType);
		*/
		//, '/production'  t($templateType, $base = THELIA_TEMPLATE_DIR)
		        $list = $exclude = array();
				$base = THELIA_TEMPLATE_DIR;
				/*$variable = Config::buildModelCriteria();
				$variable->filterByName('active-front-template', Criteria::IN);*/
				$subdir= TemplateDefinition::FRONT_OFFICE_SUBDIR . '/' . $this->getArg('template-active')->getValue();;
                $baseDir = rtrim($base, DS).DS.$subdir;

                try {
                    // Every subdir of the basedir is supposed to be a template.
                    $di = new \DirectoryIterator($baseDir);

                    /** @var \DirectoryIterator $file */
                    foreach ($di as $file) {
                        // Ignore 'dot' elements
                        if ($file->isDot() || $file->isDir()) continue;

                        // Ignore reserved directory names
                        if (in_array($file->getFilename(), $exclude)) continue;

                        $list[] = new TemplateDefinition($file->getFilename(), $templateType);
                    }
                } catch (\UnexpectedValueException $ex) {
                    // Ignore the exception and continue
                }
       

        return $list;
		
		
		
		
		
		
		
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $template) {

            $loopResultRow = new LoopResultRow($template);

            $loopResultRow
                ->set("NAME"          , str_replace('.html', '', $template->getName()))
                ->set("FILE"          , $template->getName())
                ->set("RELATIVE_PATH" , $template->getPath())
                ->set("ABSOLUTE_PATH" , $template->getAbsolutePath())
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
