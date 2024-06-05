<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function rel_to_user(){
        return $this->belongsTo(UserLogin::class, 'user_id');
    }
    public function rel_to_blog(){
        return $this->belongsTo(BlogAdd::class, 'post_id');
    }

     // Relationship to the parent comment
     public function parent()
     {
         return $this->belongsTo(Comments::class, 'parent_id');
     }
 
     // Relationship to child comments
     public function children() {
        return $this->hasMany(Comments::class, 'parent_id')->with('children');
    }
   
   
    
 
}
