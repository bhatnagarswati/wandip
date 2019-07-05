<?php

namespace App\v1\Stores;

use Illuminate\Database\Eloquent\Model;
use App\v1\Servicers\Servicer;
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
    protected $fillable = ['storeTitle', 'storeDescription', 'storeLocation', 'storePic', 'storeLong', 'storeLat', 'servicerId', 'isActive', 'languageType'];

    public function service_provider()
    {
        return $this->belongsTo(Servicer::class, 'servicerId');
    }
}
