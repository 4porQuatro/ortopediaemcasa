<?php

namespace App\Models\Items;

use App\Lib\Model;
use App\Lib\Store\Price;

use App\Models\Store\Tax;
use App\Models\User;

use Gloudemans\Shoppingcart\Contracts\Buyable;

use App\Traits\LapBootTrait;

class Item extends Model implements Buyable
{
	use LapBootTrait;

	protected $dates = [
		'starts_at',
		'created_at',
		'updated_at'
	];

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
     *	Get brand.
     *
     *	@return ItemBrand
     */
    public function itemBrand()
    {
        return $this->belongsTo(ItemBrand::class);
    }

	/**
	 *	Get category.
	 *
	 *	@return ItemCategory
	 */
	public function itemCategory()
	{
		return $this->belongsTo(ItemCategory::class);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function itemAttributeValues()
    {
        return $this->belongsToMany(ItemAttributeValue::class);
    }

	/**
	 *	Get item's tax
	 *
	 *	@return Tax
	 */
	public function tax(){
		return $this->belongsTo(Tax::class);
	}

	/**
	 * Get related items
	 *
	 * @return Collection
	 */
	public function relatedItems()
	{
		return $this->belongsToMany(Item::class, 'items_related', 'item_id', 'related_item_id');
	}

	/**
	 * Get related users
	 *
	 * @return Collection
	 */
	public function users()
	{
		return $this->belongsToMany(User::class, 'wishlist_items', 'item_id', 'user_id');
	}


	/*
	|--------------------------------------------------------------------------
	| Navigation
	|--------------------------------------------------------------------------
	*/

	public function getPrev()
	{
		return self::where('priority', '<', $this->priority)
					->whereHas('category', function($query){
						$query->where('id', $this->category_id);
					})
					->orderBy('priority', 'DESC')
					->first();
	}

	public function getNext()
	{
		return self::where('priority', '>', $this->priority)
					->whereHas('category', function($query){
						$query->where('id', $this->category_id);
					})
					->orderBy('priority', 'ASC')
					->first();
	}


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

	/**
	 * Get formatted price
	 */
	public function getFormattedPriceAttribute(){
		return Price::output($this->price);
	}

	/**
	 * Get formatted promo price
	 */
	public function getFormattedPromoPriceAttribute(){
		return Price::output($this->promo_price);
	}

    /**
     * Get formatted final price
     */
    public function getFinalPriceAttribute(){
        $price = ($this->promo_price > 0 && $this->promo_price < $this->price) ? $this->promo_price : $this->price;

        return number_format($price, 2);
    }


    /*
    |--------------------------------------------------------------------------
    | Buyable interface
    |--------------------------------------------------------------------------
    */

    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        return $this->title;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice($options = null)
    {
        $price = ($this->promo_price > 0 && $this->promo_price < $this->price) ? $this->promo_price : $this->price;
        return $price / (1 + $this->tax->percentage / 100);
    }
}
