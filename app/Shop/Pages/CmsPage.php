<?php

namespace App\Shop\Pages;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cms_pages';

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
    protected $fillable = ['title', 'shortDescription', 'fullDescription', 'pagePic', 'languageType', 'metaTitle', 'metaDescription', 'metaKeywords', 'pageType', 'status'];

    
}
