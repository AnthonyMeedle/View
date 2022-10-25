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

namespace View\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Log\Tlog;
use View\Event\ViewEvent;
use View\Form\ViewForm;

/**
 * Class ViewController
 * @package View\Controller
 */
class ViewController extends BaseAdminController
{
    public function createAction($source_id, Request $request, EventDispatcherInterface $dispatcher)
    {
        $form = $this->createForm('view.create');

        try {
            $viewForm = $this->validateForm($form);

            $data = $viewForm->getData();


            $event = new ViewEvent(
                $data['view'],
                $data['source'],
                $data['source_id']
            );

            if ((int) $data['has_subtree'] !== 0) {
                $event
                    ->setChildrenView($data['children_view'])
                    ->setSubtreeView($data['subtree_view']);
            }

            $dispatcher->dispatch($event, 'view.create');

            return $this->generateSuccessRedirect($form);
        } catch (\Exception $ex) {
            $error_message = $ex->getMessage();

            Tlog::getInstance()->error("Failed to validate View form: $error_message");
        }

        $this->setupFormErrorContext(
            'Failed to process View form data',
            $error_message,
            $form
        );

        $sourceType = $request->get('source_type');

        return $this->render(
            $sourceType . '-edit',
            [
                $sourceType . '_id' => $source_id,
                'current_tab' => 'modules'
            ]
        );
    }
}
