<?php

namespace App\Shop\Pumps;

use Illuminate\Database\Eloquent\Model;
use App\Shop\Stores\Store;

class Pump extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pumps';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pumpId';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['storeId', 'servicerId', 'adminId', 'pumpTitle', 'pumpDescription', 'languageType', 'pumpAddress',  'pumpLat', 'pumpLong',  'pumpPrice', 'pumpPic', 'status'];

    public function stores()
    {
        return $this->belongsTo(Store::class, 'storeId');
    }
}
