<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 27/08/2019
 * Time: 13:41
 */

namespace View\Hook;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Template\Parser\ParserResolver;
use Thelia\Core\Template\TheliaTemplateHelper;
use Thelia\Model\CategoryQuery;
use Thelia\Model\ContentQuery;
use Thelia\Model\FolderQuery;
use Thelia\Model\ProductQuery;
use View\Event\FindViewEvent;
use View\Model\View;
use View\Model\ViewQuery;
use View\Service\FrontViewFinder;

class HookManager extends BaseHook
{
    private const SOURCE_CONFIGURATION = [
        'category' => [
            'argument' => 'category_id',
            'label' => 'this category',
            'subtree_label' => 'sub-categories',
            'children_label' => 'products',
        ],
        'content' => [
            'argument' => 'content_id',
            'label' => 'this content',
        ],
        'folder' => [
            'argument' => 'folder_id',
            'label' => 'this folder',
            'subtree_label' => 'sub-folders',
            'children_label' => 'contents',
        ],
        'product' => [
            'argument' => 'product_id',
            'label' => 'this product',
        ],
    ];

    public function __construct(
        private readonly FrontViewFinder $frontViewFinder,
        #[Autowire(service: 'thelia.template_helper')]
        private readonly TheliaTemplateHelper $templateHelper,
        private readonly RequestStack $requestStack,
        ?EventDispatcherInterface $dispatcher = null,
        ?ParserResolver $parserResolver = null,
    ) {
        parent::__construct($dispatcher, $parserResolver);
    }

    public static function getSubscribedHooks(): array
    {
        return [
            'module.configuration' => [
                ['type' => 'back', 'method' => 'onModuleConfiguration'],
            ],
            'category.tab-content' => [
                ['type' => 'back', 'method' => 'onEditModuleTab'],
            ],
            'content.tab-content' => [
                ['type' => 'back', 'method' => 'onEditModuleTab'],
            ],
            'folder.tab-content' => [
                ['type' => 'back', 'method' => 'onEditModuleTab'],
            ],
            'product.tab-content' => [
                ['type' => 'back', 'method' => 'onEditModuleTab'],
            ],
        ];
    }

    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        if (!$this->usesTwigBackOffice()) {
            $event->add($this->render('ViewConfiguration.html'));

            return;
        }

        $rows = [];
        /** @var View $assignment */
        foreach (ViewQuery::create()->orderBySource()->orderBySourceId()->find() as $assignment) {
            $rows[] = [
                'assignment' => $assignment,
                'title' => $this->getObjectTitle($assignment->getSource(), $assignment->getSourceId()),
            ];
        }

        $event->add(
            $this->render('View/configuration.html.twig', ['rows' => $rows])
        );
    }

    public function onEditModuleTab(HookRenderEvent $event): void
    {
        $source = (string) $event->getArgument('view');
        if (!isset(self::SOURCE_CONFIGURATION[$source])) {
            return;
        }

        $configuration = self::SOURCE_CONFIGURATION[$source];
        $sourceId = (int) (
            $event->getArgument($configuration['argument'])
            ?? $event->getArgument($source)
            ?? 0
        );

        if ($sourceId < 1) {
            return;
        }

        if (!$this->usesTwigBackOffice()) {
            $event->add($this->render('View-'.$source.'.html'));

            return;
        }

        $assignment = ViewQuery::create()
            ->filterBySource($source)
            ->filterBySourceId($sourceId)
            ->findOne();

        $resolvedEvent = new FindViewEvent($sourceId, $source);
        $this->dispatcher?->dispatch($resolvedEvent, FindViewEvent::FIND);

        $event->add(
            $this->render('View/editor.html.twig', [
                'source_type' => $source,
                'source_id' => $sourceId,
                'source_configuration' => $configuration,
                'assignment' => $assignment,
                'resolved_view' => $resolvedEvent->getView(),
                'resolved_source_title' => $resolvedEvent->getViewObject() instanceof View
                    ? $this->getObjectTitle(
                        $resolvedEvent->getViewObject()->getSource(),
                        $resolvedEvent->getViewObject()->getSourceId()
                    )
                    : null,
                'front_views' => $this->frontViewFinder->find(),
            ])
        );
    }

    private function usesTwigBackOffice(): bool
    {
        return str_ends_with($this->templateHelper->getActiveAdminTemplate()->getName(), '-twig');
    }

    private function getObjectTitle(string $source, int $sourceId): string
    {
        $object = match ($source) {
            'category' => CategoryQuery::create()->findPk($sourceId),
            'content' => ContentQuery::create()->findPk($sourceId),
            'folder' => FolderQuery::create()->findPk($sourceId),
            'product' => ProductQuery::create()->findPk($sourceId),
            default => null,
        };

        if (null === $object) {
            return '#'.$sourceId;
        }

        $locale = $this->requestStack->getCurrentRequest()?->getLocale() ?: 'en_US';

        return (string) $object->setLocale($locale)->getTitle();
    }
}
