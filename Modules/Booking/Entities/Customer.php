<?php

namespace Modules\Booking\Entities;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Customer extends Model
{
    use Cachable;
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    public $sortable = [
        'id',
        'name',
        'email',
        'phone',
        'updated_at',
        'gender'
    ];
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
    protected $fillable = ['name', 'email', 'phone', 'phone_other', 'gender', 'address', 'permanent_address', 'facebook', 'zalo', 'creator_id'];

    /**
     * Text gender: 1 - Name, 0 - Nu
     * @return string
     */
    public function getTextGenderAttribute(){
        return $this->gender===1?__('message.user.gender_male'):($this->gender===0?__('message.user.gender_female'):"");
    }

    public function setPhoneOtherAttribute($value){
        if (is_array($value)){
            $value_arr = [];
            foreach ($value as $key => $value){
                if (empty(trim($value))) continue;
                $value_arr[] = $value;
            }
            $this->attributes['phone_other'] = implode(', ', $value_arr);
        }
    }
	public function getPhoneOtherAttribute($value)
	{
		if(!empty($value))
			return explode(', ', $value);
		return [];
	}

	public static function boot(){
        parent::boot();

        static::creating(function ($model) {
            if(\Auth::check())
                $model->creator_id = \Auth::id();
        });
    }
}
