<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Agent extends Model
{
    use Sortable, BaseModel;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'agents';

    public static $savePath = '/images/agents/';

    public $sortable = ['id',
        'name',
        'phone',
        'email',
        'address',
        'birthday',
        'updated_at'
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
    protected $fillable = ['name', 'phone', 'email', 'address', 'birthday', 'logo'];

    public function user(){
        return $this->hasMany('App\User');
    }

    public static function saveLogo($file){
        if(empty($file)) return;
        $fileName = time().'_'.$file->getClientOriginalName();
        $path = self::$savePath . $fileName;
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has(self::$savePath)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').self::$savePath);
        }
        \Image::make($file->getRealPath())->resize(100,100)->save(public_path('/storage/').$path);
        return  config('filesystems.disks.public.visibility').$path;
    }
	public static function boot()
	{
		parent::boot();
/*		static::addGlobalScope('byCompany', function (Builder $builder) {
			static::bootGlobalScopeAgent($builder);
		});*/
		static::creating(function($model) {
			static::bootCreatingByRole($model);
		});
		static::deleted(function($model) {
			if ($model->logo) {
				\Storage::delete($model->logo);
			}
		});
	}
}
