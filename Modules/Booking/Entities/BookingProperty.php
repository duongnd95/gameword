<?php

namespace Modules\Booking\Entities;

use App\ModuleInfo;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BookingProperty extends Model
{
    use Cachable;
	public $timestamps = false;

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['key', 'name', 'type', 'data', 'module'];
    public static $saveFolderImage = '/images/booking/';
    public static $saveFolderFile = '/files/booking/';

	public function bookings(){
	    return $this->belongsToMany('Modules\Booking\Entities\Booking', 'booking_property_values');
    }

    public static function saveFile($file, $type){
        if (empty($file)) return;

        $fileName = time().'_'.$file->getClientOriginalName();
        $path = ($type == 'image') ? self::$saveFolderImage : self::$saveFolderFile;
        if (!\Storage::disk(config('filesystems.disks.public.visibility'))->has($path)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$path);
        }
        if ($type == 'image'){
            \Image::make($file->getRealPath())->resize(1024, 1024)->save(public_path('/storage').$path.$fileName);
        }else{
            $file->move(public_path('/storage').$path, $fileName);
        }
        return config('filesystems.disks.public.visibility').$path.$fileName;
    }

    /**
     * get data select in file config
     * @param $id_service //id dịch vụ
     * @param $moduleName //tên module
     * @return mixed
     */
    public static function getDataSelect($id_service = null, $moduleName){
        $moduleInfo = new ModuleInfo($moduleName);
        $service = $moduleInfo->getBookingServiceInfo();
        $data_arr = [];
        foreach ($service['properties'] as $key => $value){
            if ($value['type'] == 'select'){
                $func_select = $value['data']['select'];
                $data_arr['properties['.$value['key'].']'] = $service['namespaceModel']::$func_select($id_service);
            }
        }
        return $data_arr;
    }
}
