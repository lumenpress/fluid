<?php

namespace LumenPress\Nimble\Models;

use LumenPress\Nimble\Collections\MenuCollection;

class Menu extends Taxonomy implements \IteratorAggregate, \Countable
{
    protected $with = ['term', 'items'];

    protected $taxonomy = 'nav_menu';

    /**
     * Override newCollection() to return a custom collection.
     *
     * @param array $models
     *
     * @return \LumenPress\Nimble\Collections\MenuCollection
     */
    public function newCollection(array $models = [])
    {
        return new MenuCollection($models);
    }

    /**
     * Relationship with Posts model.
     *
     * @return Illuminate\Database\Eloquent\Relations
     */
    public function items()
    {
        return $this->belongsToMany(MenuItem::class,
            'term_relationships', 'term_taxonomy_id', 'object_id')->orderby('menu_order');
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items->all());
    }

    public function count()
    {
        return $this->items->count();
    }
}
