<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'category', 'image_url'];

        public function scopeSearch($query, $value)
        {
            if (isset($value) && $value != null && $value != "" && strlen($value) > 0) {
                $query->where(function ($query) use ($value) {
                    $query->where('name', 'LIKE', '%' . $value . '%');
                });                
            }
        }
  
        public function scopeCategory($query, $value)
        {
            if (intval($value) >= 0) {
                $query->where('category', '=', $value);
            }
        } 

        public function scopeImageUrl($query, $value)
        {
            if (intval($value) >= 0) {
                $query->where('image_url', '=', $value);
            }
        } 
    
        public function scopePagination($query, $params)
        {
            if (isset($params['length'])) {
                $query->offset($params['start'])->limit($params['length']);
            }
        }
    
        public function scopeFilters($query, $params)
        {
    
            $query->select('products.*')->from('products');
          
            //Insert Search Parameters
            if (isset($params['search'])) {
                $query->search($params['search']);
            }
    
            //Insert Extra Filters Parameters
           if (isset($params['filters']) && $params['filters'] !== null) {
    
                if (isset($params['filters']['category'])) {
                    $query->Category($params['filters']['category']);
                }

                if (isset($params['filters']['image_url'])) {
                    $query->ImageUrl($params['filters']['image_url']);
                }
            } 
    
            if (isset($params['order']['fieldName'])) {
                $query->orderBy($params['order']['fieldName'], $params['order']['direction']);
            } 
        }
}
