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

namespace View\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use View\Event\ViewEvent;

/**
 * Class ViewController
 * @package View\Controller
 */
class ViewController extends BaseAdminController
{
    private const SOURCES = ['category', 'content', 'folder', 'product'];

    #[Route('/admin/view/add/{source_id}', name: 'view.add', methods: ['POST'], requirements: ['source_id' => '\\d+'])]
    public function createAction(int $source_id, EventDispatcherInterface $dispatcher): Response
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['View'], AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm('view_form');

        try {
            $viewForm = $this->validateForm($form);
            $data = $viewForm->getData();

            if ($source_id !== (int) $data['source_id'] || !\in_array($data['source'], self::SOURCES, true)) {
                throw new \InvalidArgumentException('Invalid view source.');
            }

            $event = new ViewEvent(
                (string) $data['view'],
                $data['source'],
                $source_id
            );

            if ((int) $data['has_subtree'] !== 0) {
                $event
                    ->setChildrenView((string) $data['children_view'])
                    ->setSubtreeView((string) $data['subtree_view']);
            }

            $dispatcher->dispatch($event, ViewEvent::CREATE);

            return $this->generateSuccessRedirect($form);
        } catch (\Throwable $exception) {
            $this->addFlash('danger', $exception->getMessage());

            return $this->generateErrorRedirect($form);
        }
    }
}
