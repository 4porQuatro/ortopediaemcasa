<?php

namespace App\Repositories;

use App\Models\Items\Item;
use App\Models\Items\Type;
use App\Models\Items\ItemCategory;
use App\Models\Items\ItemAttributeType;

class ItemRepository
{
    /**
     * Search for all the types and joins them with the categories.
     *
     * @return Collection
     */
    public function getTree()
    {
        return Type::with('categories')->get();
    }


    /**
     * Search for an item matching the slug. It also joins the item
     * with its category (and category's type) and related items.
     *
     * @param  string $slug
     *
     * @return Item
     */
    public function getItem($slug)
    {
        return Item::where('slug', $slug)
            ->with('category.type')
            ->with(
                [
                    'related' => function($query){
                        $query->take(4);
                    },
                    'colors' => function($query){
                        $query->where('stock', '>', 0)->distinct();
                    }
                ]
            )
            ->first();
    }


    /**
     * Get highlighted items
     *
     * @return Collection
     */
    public function getHighlights()
    {
        return Item::where('highlight', 1)->take(4)->get();
    }


    /**
     * Get highlighted categories
     *
     * @return Collection
     */
    public function getHighlightedCategories()
    {
        return ItemCategory::where('highlight', 1)
            ->whereHas('type', function($query){
                $query->where('active', 1);
            })
            ->with('type')
            ->take(6)
            ->get();
    }


    /**
     * Search for a category matching the slug. If the size is indicated,
     * we must only join the items that have that size available.
     *
     * @param  string $slug
     * @param  ItemAttributeType $size
     *
     * @return ItemCategory
     */
    public function getCategory($slug, ItemAttributeType $size = null)
    {
        $builder = ItemCategory::where('slug', $slug);

        if(!$size)
        {
            $builder = $builder->with('items');
        }
        else
        {
            $builder = $builder->with([
                'items' => function($query) use($size)
                {
                    $query->whereHas('sizes', function($query) use($size){
                        $query->where('size_id', $size->id);
                    });
                }
            ]);
        }

        return $builder->first();
    }
}
