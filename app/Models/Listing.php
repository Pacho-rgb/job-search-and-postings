<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    
    // protected $fillable = ['company', 'title', 'location', 'email', 'website', 'tags', 'description'];

    public function scopeFilter($query, array $filters)
    {
        // It might be seen as intimidating, but the $query parameter here is just the database connection variable
        if($filters['tag'] ?? false){
            $query->where('tags', 'like', "%".request('tag')."%");
        }

        if($filters['search'] ?? false){
            $query->where('title', 'like', "%".request('search')."%")
                ->orWhere('description', 'like', "%".request('search')."%")
                ->orWhere('tags', 'like', "%".request('search')."%");
        }
    }
    // Relationhip to user
    public function user()
    {
        // Note that a listing belongs to a particular user
        return $this->belongsTo(User::class, 'user_id');
    }
}
