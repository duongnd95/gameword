<?php

namespace App;

use App\Traits\ModelEventLogger;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use ModelEventLogger;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'versions';

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
    protected $fillable = ['version', 'version_number', 'version_ios', 'version_number_ios', 'required_version_android', 'required_version_ios', 'store_android',
        'store_ios', 'enable_ads', 'contact', 'content'];
}
