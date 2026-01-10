<?php
namespace App;

use Modules\Booking\Entities\BookingProperty;

class ModuleInfo {

	private $module;
	private $config;
	private $namespaceModel;
	private $moduleName;
	/**
	 * ModuleInfo constructor.
	 */
	public function __construct($moduleName) {
		$this->module = \Module::find($moduleName);
		if(!$this->module || !$this->module->active) abort(400, "Module không tồn tại hoặc chưa được kích hoạt!");
		$this->config = config($moduleName);
		$this->namespaceModel = '\\'.config('modules.namespace').'\\'.$this->module->getName().'\\'.config('modules.paths.generator.model.path') .'\\';
		$this->moduleName = $moduleName;
	}
	public function getModule(){
		return $this->module;
	}
	public function getConfig(){
		return $this->config;
	}
	public function getNamespaceModel(){
		return $this->namespaceModel;
	}
	public function getModuleName(){
	    return $this->moduleName;
    }
	public function getBookingServiceInfo(){
		if(empty($this->config['booking']) || !$this->config['booking']['active']) abort(400, "Module chưa cấu hình chức năng Booking!");
		$model = $this->namespaceModel. $this->config['booking']['model'];
		return array_merge([
			'label' => __($this->module->alias."::bookings.".$this->module->alias),
			'namespaceModel' => $model,
			'table' => with(new $model)->getTable(),
            'moduleName' => $this->moduleName
		], $this->config['booking']);
	}
	public function configBookingProperties(){
	    $config = $this->config['booking']['properties'];
        $booking_properties = new BookingProperty();
        $value_properties = [];
        if (!empty($config)){
            foreach ($config as $key => $value){
                $data = json_encode($value['data']);
                if (BookingProperty::where([['key','=', $value['key']],['module','=', $this->moduleName]])->count() == 0){
                    $value_properties[] = [
                        'key' => $value['key'],
                        'name' => $value['name'],
                        'type' => $value['type'],
                        'data' => $data,
                        'module' => $this->moduleName
                    ];
                }
            }
        }
        if (count($value_properties) > 0){
            $booking_properties->insert($value_properties);
        }
	}
}