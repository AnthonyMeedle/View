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

namespace View\Form;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Form\BaseForm;
use Thelia\Core\Translation\Translator;
use View\View;


/**
 * Class ViewForm
 * @package View\Form
 * @author manuel raynaud <mraynaud@openstudio.fr>
 */
class ViewForm extends BaseForm
{

    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     * $this->formBuilder->add("name", "text")
     *   ->add("email", "email", array(
     *           "attr" => array(
     *               "class" => "field"
     *           ),
     *           "label" => "email",
     *           "constraints" => array(
     *               new \Symfony\Component\Validator\Constraints\NotBlank()
     *           )
     *       )
     *   )
     *   ->add('age', 'integer');
     *
     * @return null
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add('view', 'text', array(
                'label' => Translator::getInstance()->trans('View name', [], View::DOMAIN),
                'label_attr' => array(
                    'for' => 'view_view'
                )
            ))
            ->add('has_subtree', 'integer', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'label' => Translator::getInstance()->trans('Object with subtree', [], View::DOMAIN),
                'label_attr' => array(
                    'for' => 'view_has_subtree'
                )
            ))

            ->add('subtree_view', 'text', array(
                'required' => false,
                'label' => Translator::getInstance()->trans('Sub-tree view name', [], View::DOMAIN),
                'label_attr' => array(
                    'for' => 'view_subtree_view'
                )
            ))
            ->add('children_view', 'text', array(
                'required' => false,
                'label' => Translator::getInstance()->trans('Children view name', [], View::DOMAIN),
                'label_attr' => array(
                    'for' => 'view_children_view'
                )
            ))

            ->add('source', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'label' => Translator::getInstance()->trans('Source type', [], View::DOMAIN),
                'label_attr' => array(
                    'for' => 'view_source'
                )
            ))
            ->add('source_id', 'integer', array(
                'constraints' => array(
                    new NotBlank(),
                    new GreaterThan([ 'value' => 0])
                ),
                'label' => Translator::getInstance()->trans('Source object ID', [], View::DOMAIN),
                'label_attr' => array(
                    'for' => 'view_source_id'
                )
            ))

        ;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "view_form";
    }
}