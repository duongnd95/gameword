<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class User extends Authenticatable
{
	use Cachable, Notifiable, HasRoles, Sortable, SoftDeletes, HasApiTokens, BaseModel;

	public $sortable = [
		'id',
		'name',
		'email',
		'active',
		'username',
		'agent_id'
	];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'active', 'username', 'agent_id'
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * Get the profile record associated with the user.
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function profile(){
		return $this->hasOne('App\UserProfiles');
	}
	public function agent() {
		return $this->belongsTo('App\Agent');
	}

    public function coupons(){
	    return $this->hasMany('App\Coupon', 'user_id');
    }


	/**
	 * Scope a query to only include active users.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeActive($query)
	{
		return $query->where('active', Config("settings.active"));
	}
	public function scopeCustomer($query)
	{
		return $query->whereHas('roles', function($query){
			$query->where('name', config('settings.roles.customer'));
		});
	}
	/**
	 * Show avatar
	 * @return string|void
	 */
	public function showAvatar($attrs = null,$default = ""){
		if(isset($this->profile) && !empty($this->profile->avatar)){
			$avatar = $this->profile->avatar;
			if(\Storage::exists($avatar))
				return '<img alt="avatar" class="'.$attrs["class"].'" src="'.asset(\Storage::url($avatar)).'" />';
		}
		if(!empty($default)) return '<img alt="avatar" class="'.$attrs["class"].'" src="'.$default.'" />';
		return;
	}

	public function findForPassport($username) {
		return $this->where('username', $username)->where('active', Config("settings.active"))->first();
	}

	/**
	 * Token api
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function tokens() {
		return $this->hasMany('Laravel\Passport\Token');
	}

	/**
	 * Revoke tokens - this user
	 */
	public function revokeAllTokens() {
		$tokens = $this->tokens()->where('revoked', 0)->get();
		foreach ($tokens as $token){
			$token->revoke();
		}
	}
	public function deviceNotifies() {
		return $this->hasMany('App\DeviceNotify');
	}
	public function roleBelongToCompany(){
		return $this->hasRole(config('settings.roles.company_admin'))
		       || $this->hasRole(config('settings.roles.company_employee'))
		       || $this->hasRole(config('settings.roles.company_booking'));
	}
	public function roleBelongToAgent(){
		return $this->hasRole(config('settings.roles.agent_admin')) || $this->hasRole(config('settings.roles.agent_employee'));
	}
	public function roleBelongToCustomer(){
		return $this->hasRole(config('settings.roles.customer'));
	}
//	public function roleBelongToBranch(){
//	    return $this->hasRole(config('settings.roles.branch_1')) || $this->hasRole(config('settings.roles.branch_2'));
//    }

	public function isAdminCompany(){
		return $this->hasRole(config('settings.roles.company_admin'));
	}
	public function isAdminAgent(){
		return $this->hasRole(config('settings.roles.agent_admin'));
	}
//	public function isAdminBranch(){
//	    return $this->hasRole(config('settings.roles.branch_1'));
//    }
	public function isCompanyManagerBooking(){
		return $this->hasRole(config('settings.roles.company_booking'));
	}


	public function isEmployeeCompany(){
		return $this->hasRole(config('settings.roles.company_employee'));
	}
	public function isEmployeeAgent(){
		return $this->hasRole(config('settings.roles.agent_employee'));
	}
//	public function isEmployeeBranch(){
//	    return $this->hasRole(config('settings.roles.branch_2'));
//    }

	public static function boot()
	{
		parent::boot();
		static::creating(function ($user) {
			static::bootCreatingByRole($user);
            $user->member_card = self::getCodeUnique();
		});

		static::updated(function ($user) {
			//when inactive user, revoke all tokens of this user
			if($user->active === \Config::get("settings.inactive"))
				$user->revokeAllTokens();
		});

		static::saving(function ($user) {

		});
		static::deleted(function ($user) {
			$user->revokeAllTokens();
			//check xóa hoàn toàn trong CSDL
			if ($user->isForceDeleting()) {
				$profile = $user->profile;
				if ( ! empty( $profile->avatar ) ) {
					\Storage::delete( $profile->avatar );
				}
			}
		});
	}

    static function getCodeUnique($length = 8){
        $codeAlphabet= "0123456789";
        $max = strlen($codeAlphabet); // edited

        do{
            $code = "";
            for ($i=0; $i < $length; $i++) {
                $code .= $codeAlphabet[random_int(0, $max-1)];
            }
        }while(User::withTrashed()->where('member_card', $code)->count() != 0);

        return $code;
    }

	/**
	 * Users belong to my company (not agent)
	 * @return mixed
	 */
	public static function getUsersByOnlyCompany(){
		return User::whereHas('roles', function($query){
			$query->whereIn('name', [config('settings.roles.company_admin'),config('settings.roles.company_employee'),config('settings.roles.company_booking')]);
		})->pluck( 'name', 'id' );
	}

	/**
	 * Users belong to my agent
	 * @return mixed
	 */
	public static function getUsersByOnlyAgent(){
		return User::where( 'agent_id', '=', \Auth::user()->agent_id )
			->whereHas('roles', function($query){
				$query->whereIn('name', [config('settings.roles.agent_admin'),config('settings.roles.agent_employee')]);
			})->pluck( 'name', 'id' );
	}
	/**
	 * Users belong to Customer
	 * @return mixed
	 */
	public static function getUsersByOnlyCustomer(){
		return User::whereHas('roles', function($query){
			$query->where('name', config('settings.roles.customer'));
		})->pluck( 'name', 'id' );
	}
    /**
     * Users belong to my branch
     * @return mixed
     */
    public static function getUsersByOnlyBranch(){
        return User::where( 'branch_id', '=', \Auth::user()->branch_id )
            ->pluck( 'name', 'id' );
    }

	static public function uploadAndResize($image, $width = 100, $height = null){
        if(empty($image)) return;
        $folder = "/images/avatar/";
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.'.$fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder.$timestamp. '-' .$filename.'.'.$fileExt);

        $img = \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(storage_path('app/public').$pathImage);

        return config('filesystems.disks.public.path').$pathImage;
    }
}
