<?php

namespace App\Models\Items;

use App\Lib\Model;

use App\Traits\LapBootTrait;
use Illuminate\Database\Eloquent\Collection;

class ItemCategory extends Model
{
	use LapBootTrait;

    protected static $parent = "parent_id";

	/**
	 *	Get language
	 *
	 *	@return Language
	 */
	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}

	/**
	 * Get category's items
	 *
	 * @return Relation
	 */
	public function items(){
		return $this->hasMany(Item::class);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function itemAttributeTypes()
    {
        return $this->hasMany(ItemAttributeType::class);
    }



    /**
     * Finds the node root.
     *
     * @return Tree $root
     */
    public function root()
    {
        $root = (!$this->{static::$parent}) ? $this : static::rootRecursive($this);

        return $root;
    }

    /**
     * Recursively finds a node root.
     *
     * @param  Tree $node
     * @return Tree $node
     */
    private static function rootRecursive($node)
    {
        if ($node->{static::$parent})
        {
            $node = static::rootRecursive(static::where('id', $node->{static::$parent})->first());
        }

        return $node;
    }

    /**
     * Get parent.
     *
     * @return static
     */
    public function parent()
    {
        return $this->belongsTo(static::class, static::$parent, $this->getKeyName());
    }

    /**
     * Get children.
     *
     * @return static
     */
    public function children()
    {
        return $this->hasMany(static::class, static::$parent, $this->getKeyName());
    }


    /**
     * Renders the tree as HTML.
     *
     * @param Collection $nodes
     * @param $wrapper
     * @param $item_tag
     * @param callable $render
     * @param null $parent
     * @return string
     */
    public static function render(Collection $nodes, $wrapper, $item_tag, Callable $render, $parent = null)
    {
        $nodes = static::treeToArray($nodes);

        $output = static::renderRecursive($nodes, $wrapper, $item_tag, $render, $parent = null);

        return $output;
    }

    /**
     * Recursively renders the tree as HTML.
     *
     * @param array $nodes
     * @param $wrapper
     * @param $item_tag
     * @param callable $render
     * @param null $parent
     * @return string
     */
    public static function renderRecursive(array $nodes, $wrapper, $item_tag, Callable $render, $parent = null)
    {
        $output = '';

        if(sizeof($nodes))
        {
            $output .= '<' . $wrapper . '>';

            foreach($nodes[$parent] as $id => $node)
            {
                $output .= $render($node);

                if(isset($nodes[$id]))
                {
                    $output .= static::renderRecursive($nodes, $wrapper, $item_tag, $render, $id);
                }

                $output .= '</' . $item_tag . '>';
            }

            $output .= '</' . $wrapper . '>';
        }

        return $output;
    }

    /**
     * Creates an array indexed by the node's parent_id id attributes.
     *
     * @param Collection $nodes
     * @return array
     */
    public static function treeToArray(Collection $nodes)
    {
        $arr = [];

        if($nodes->count())
        {
            foreach($nodes as $node)
            {
                $arr[$node->{static::$parent}][$node->id] = $node;
            }
        }

        return $arr;
    }
}
