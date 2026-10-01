<?php

declare(strict_types=1);

namespace View\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Core\Template\TheliaTemplateHelper;

final readonly class FrontViewFinder
{
    public function __construct(
        #[Autowire(service: 'thelia.template_helper')]
        private TheliaTemplateHelper $templateHelper,
    ) {
    }

    /**
     * Return the public, root-level views exposed by the active front-office theme.
     * The active theme wins when it overrides a view inherited from a parent theme.
     *
     * @return list<array{name: string, file: string, relative_path: string, absolute_path: string}>
     */
    public function find(): array
    {
        $activeTemplate = $this->templateHelper->getActiveFrontTemplate();
        $templates = array_merge([$activeTemplate], array_values($activeTemplate->getParentList() ?? []));
        $internalViews = array_flip($activeTemplate->getInternalViews());
        $views = [];

        /** @var TemplateDefinition $template */
        foreach ($templates as $template) {
            $path = $template->getAbsolutePath();
            if (!is_dir($path)) {
                continue;
            }

            $finder = Finder::create()
                ->files()
                ->depth('== 0')
                ->in($path)
                ->ignoreVCS(true)
                ->ignoreDotFiles(true)
                ->name(['*.html.twig', '*.html'])
                ->sortByName();

            foreach ($finder as $file) {
                $name = preg_replace('/\\.html(?:\\.twig)?$/', '', $file->getFilename());

                if (null === $name || isset($internalViews[$name]) || isset($views[$name])) {
                    continue;
                }

                $views[$name] = [
                    'name' => $name,
                    'file' => $file->getFilename(),
                    'relative_path' => $file->getRelativePath(),
                    'absolute_path' => $file->getPath(),
                ];
            }
        }

        ksort($views, SORT_NATURAL | SORT_FLAG_CASE);

        return array_values($views);
    }
}
