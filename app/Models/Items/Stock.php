<?php

namespace App\Models\Items;

use App\Lib\Model;

class Stock extends Model{

    protected $table = 'items_stocks';

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public static function byColor($item_id, $color_id){
        return self::where('item_id', $item_id)->where('color_id', $color_id)->with('color')->get();
    }

    public static function bySize($item_id, $size_id){
        return self::where('item_id', $item_id)->where('size_id', $size_id)->with('size')->get();
    }

    public function color(){
        return $this->hasMany('App\Models\Item\Color', 'id', 'color_id');
    }

    public function size(){
        return $this->hasMany('App\Models\Item\Size', 'id', 'size_id');
    }

    public static function getByForeignKey($item_id, $size_id, $color_id){
        return self::where('item_id', $item_id)->where('size_id', $size_id)->where('color_id', $color_id)->first();
    }
}