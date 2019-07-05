<?php

namespace App\v1\Pumps;

use Illuminate\Database\Eloquent\Model;
use App\v1\Stores\Store;

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
    protected $fillable = ['storeId', 'servicerId', 'adminId', 'pumpTitle', 'pumpDescription', 'pumpAddress', 'pumpLat',  'pumpLong',  'pumpPrice', 'pumpMassUnit',  'pumpPic', 'status', 'languageType'];

    public function stores()
    {
        return $this->belongsTo(Store::class, 'storeId');
    }
}
