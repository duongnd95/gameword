<?php

namespace Modules\Booking\Entities;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Approve extends Model
{
    use Cachable;
    use Sortable;

    protected $table = 'approves';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $sortable = [
        'id',
        'name',
        'number',
        'color',
        'updated_at'
    ];

    protected $fillable = ['name', 'number', 'color'];

    public function bookings(){
        return $this->hasMany('Modules\Booking\Entities\Booking', 'approve_id');
    }
}
