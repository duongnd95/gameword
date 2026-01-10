<?php

namespace Modules\Product\Entities;

use App\Traits\ImageResize;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class GalleryReview extends Model
{
    use Cachable;
    use ImageResize;
    public static $imageFolder = "gallery-reviews";
    public static $imageMaxWidth = 750;
    public static $imageMaxHeight = 421;
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = ['image', 'review_id'];

    public function review()
    {
        return $this->belongsTo('Modules\Product\Entities\Review');
    }
}
