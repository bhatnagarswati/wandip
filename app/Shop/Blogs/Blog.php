<?php

namespace App\Shop\Blogs;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'shortDescription', 'fullDescription', 'languageType', 'metaTitle', 'blogImage', 'addedOn', 'metaDescription', 'metaKeywords', 'author', 'status'];

    
}
