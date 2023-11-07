# Parent-child Content

If you want to support parent-child content like subpages with a parent page, you have to include the 
[HasParent](..%2Fsrc%2FModels%2FContracts%2FHasParent.php) in the model and add the 
[HasParentTrait](..%2Fsrc%2FModels%2FConcerns%2FHasParentTrait.php) for the implementation.

## Nested URLs:
With a child-parent relationship you can add a nested URL structure, like:
```
https://www.example.com/parent-slug/child-slug
```

Given a `Page` model and `PageController`, to do this add a route to the `web.php` routes file, e.g.:

```php 
Route::get('{parent}/{page}', [PageController::class, 'childIndex'])->name('child_page_index');
```

Then add a function `childIndex` to the `PageController`:

```php 
public function childIndex(Page $parent, Page $page) {
    //check if the page is a child of the parent
    if($parent->isParentOf($child)){
        abort(Response::HTTP_NOT_FOUND);
    }

    //render the page with the regular page index function of the controller, or invoke the correct controller here:
    return $this->index($page);
}
```
