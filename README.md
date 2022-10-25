View module
===========

Using this module, you can select a specific view for any category, product, folder or content.

## Installation

```
composer require thelia/view-module:~2.0.1
```

Activate the module and go to the "Modules" tab of any category, product, folder or content configuration page.

## The loop view

Get the specific view of an object and the specific views of its sub-elements.

### Parameters

|Argument       |Description    |
|---            |---            |
|**id**         | The ID of the specific view   |
|**view**       | The Name of the specific view |
|**source**     | The type of the source associated. The possible values are `category`, `product`, `folder` or `content`   |
|**source_id**  | The ID of the source associated  |

### Output variables

|Variables      |Description    |
|---            |---            |
|$ID            | The Id of the specific view |
|$SOURCE_ID     | The ID of the source associated |
|$SOURCE        | The source associated (`category`, `product`, `folder` or `content`)|
|$VIEW          | The name of the specific view |
|$SUBTREE_VIEW  | The name of the specific view associated with the sub-element (sub-category or sub-folder) of the source |
|$CHILDREN_VIEW | The name of the specific view associated with the children (products or contents) of the source|

### Example

```
{loop type="view" name="my-specific-view" source="content" source_id=11}...{/loop}
```

## The loop frontfiles

Return all the front office templates and their path.

### Parameters

This loop have no parameters

### Output variables

|Variables      |Description    |
|---            |---            |
|$NAME          | The template name |
|$FILE          | The file name |
|$RELATIVE_PATH | The relative path of the template |
|$ABSOLUTE_PATH | The absolute path of the template |


### Example

```
{loop type="frontfile" name="my-fo-template"}...{/loop}
```

## The loop frontview

Return view of an object if the object have a specific view.

### Parameters

|Argument      |Description    |
|---           |---            |
|**source**    | The source of the object (`category`, `product`, `folder` or `content`) |
|**source_id** | The ID of the object |


### Output variables

|Variables  |Description    |
|---        |---            |
|FRONT_VIEW | The name of the view |
|VIEW_ID    | The id of the view in the view table |


### Example

```
{loop type="frontview" name="my-frontview-loop" source="category" source_id=11 }...{/loop}
```
