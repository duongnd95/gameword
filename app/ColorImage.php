<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorImage extends Model
{
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $table = 'colors_images';

    protected $primaryKey = 'id';

    protected $fillable = ['image'];

    public function color()
    {
        return $this->belongsTo('App\Color');
    }
}
