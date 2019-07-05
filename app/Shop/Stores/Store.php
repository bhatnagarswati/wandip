<?php

namespace App\Shop\Stores;

use Illuminate\Database\Eloquent\Model;
use App\Shop\Servicers\Servicer;

class Store extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stores';

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
    protected $fillable = ['storeTitle', 'storeDescription', 'languageType', 'storeLocation', 'storeLat', 'storeLong',  'storePic', 'servicerId', 'isActive'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service_provider()
    {
        return $this->belongsTo(Servicer::class, 'servicerId');
    }
    
}
