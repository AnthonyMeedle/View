# View for Thelia 3

View lets an administrator choose the front-office Twig view used for a category,
product, folder or content. A category or folder can also define the view inherited
by its descendants and by its direct leaf objects.

## Requirements

- Thelia 3.0 or later
- PHP 8.3 or later
- The Twig back office (`default-twig`) is fully supported. The legacy Smarty back
  office templates are kept for transitional installations.

## Installation

Install the module in `local/modules/View`, refresh the module list, then activate
`View`. The installation is idempotent and does not drop an existing `view` table.

The selector appears in the **Modules** tab of category, product, folder and content
edit pages. The module configuration page lists every explicit assignment.

Only public, root-level views from the active front-office theme (and its parent
themes) are offered. With Flexy these are `.html.twig` files; their extension is not
stored in the database.

## Automatic resolution

Resolution follows this order:

1. the view directly assigned to the current object;
2. for a category or folder, the closest ancestor's subtree view;
3. for a product or content, the closest ancestor's children view;
4. the default view supplied by the active theme.

The module updates Thelia 3's `_view` request attribute before rendering, so custom
views work for rewritten URLs as well as classic query-string URLs.

## Legacy loops

The historical Smarty loop aliases remain available during migration:

- `view`
- `frontview` (also auto-registered as `front_view`)
- `frontfiles`

`frontfiles` now recognizes both `.html.twig` and `.html` views and excludes internal
theme components that cannot be rendered as pages.
