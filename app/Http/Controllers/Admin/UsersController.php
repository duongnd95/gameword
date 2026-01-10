<?php

namespace App\Http\Controllers\Admin;

use App\Agent;
use App\Branch;
use App\Http\Controllers\Controller;
use App\Role;
use App\Traits\Authorizable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\UserProfiles;
use Session;
use Validator;

class UsersController extends Controller
{
	use Authorizable;
	/**
	 * Display a listing of the resource.
	 *
	 * @return void
	 */
	public function index(Request $request)
	{
		$keyword = $request->get('search');
		$perPage = Config("settings.perpage");

		$users = User::with('profile')->orderBy('created_at','DESC');
		if (!empty($keyword)) {
			$users = $users->byRole()->sortable()->where('name', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")->orWhere('username', 'LIKE', "%$keyword%")
			             ->paginate($perPage);
		} else {
			$users = $users->byRole()->sortable()->paginate($perPage);
		}
		if(\Auth::user()->roleBelongToCompany()){
			$users->load(["roles","agent"]);
		}else{
            $users->load(["roles"]);
		}

		return view('admin.users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return void
	 */
	public function create()
	{
		$roles = Role::byRole()->pluck('label', 'name');
		$roles->prepend(__('message.please_select'), '')->all();

		if(\Auth::user()->isAdminCompany()){
			$agents = Agent::pluck('name', 'id');
			$agents->prepend(__('message.please_select'), '')->all();
		}else{
			$agents = ['' => __('message.please_select')];
		}

		return view('admin.users.create', compact('roles', 'agents'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return void
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'username' => 'required|alpha_num|min:4|max:80|unique:users',
			'password' => 'required|min:6|confirmed',
			'profile.birthday' => 'nullable|date_format:"'.config('settings.format.date').'"',
			'profile.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'agent_id' => \Auth::user()->roleBelongToCompany()?'required_if:roles.0,'.config('settings.roles.agent_admin').','.config('settings.roles.agent_employee'):'nullable',
			'roles' => 'required'
		]);

		$data = $request->except('password');
		$data['password'] = bcrypt($request->password);
		if(!isset($request->active)){
			$data["active"] = Config("settings.inactive");
		}

		$profile = new UserProfiles($data['profile']);
		if(!empty($profile["birthday"]))
			$profile["birthday"] = \DateTime::createFromFormat(config('settings.format.date'), $profile["birthday"])->format('Y-m-d');
		if($request->hasFile('profile.avatar')) {
			$profile->avatar = $profile->uploadAndResizeAvatar($request->file('profile')["avatar"]);
		}
		//save
		$user = User::create($data);

		$user->profile()->save($profile);
		//role
		foreach ($request->roles as $role) {
			$user->assignRole($role);
		}
        toastr()->success(__('message.user.created_success'));

		return redirect('admin/users');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return void
	 */
	public function show($id)
	{
		$user = User::byRole()->findOrFail($id);
		return view('admin.users.show', compact('user'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return void
	 */
	public function edit($id)
	{
		$roles = Role::byRole()->pluck('label', 'name');
		$roles->prepend(__('message.please_select'), '')->all();

		$user = User::byRole()->with(['roles','profile'])->select('id', 'name', 'email','active','username', 'agent_id')->findOrFail($id);

		$user_roles = array_pluck($user->roles, 'name');
		$profile = $user->profile;
		if(!empty($profile->birthday))
			$profile->birthday = Carbon::parse($profile->birthday)->format(config('settings.format.date'));

		if(\Auth::user()->isAdminCompany()){
			$agents = Agent::pluck('name', 'id');
			$agents->prepend(__('message.please_select'), '')->all();
		}else{
			$agents = ['' => __('message.please_select')];
		}

		return view('admin.users.edit', compact('user', 'roles', 'user_roles', 'profile', 'agents'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int      $id
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return void
	 */
	public function update($id, Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			'profile.birthday' => 'nullable|date_format:"'.config('settings.format.date').'"',
			'profile.avatar' => 'nullable|mimes:jpeg,jpg,png',
			'email' => 'required|email|max:255|unique:users,email,' . $id,
			'username' => 'required|alpha_num|min:4|max:80|unique:users,username,' . $id,
			'password' => 'nullable|min:6|confirmed',
			'agent_id' => \Auth::user()->roleBelongToCompany()?'required_if:roles.0,'.config('settings.roles.agent_admin').','.config('settings.roles.agent_employee'):'nullable',
			'roles' => 'required'
		]);

		$data = $request->except(['password']);
		if ($request->has('password') && !empty($request->password)) {
			$data['password'] = bcrypt($request->password);
		}
		if(!isset($request->active)){
			$data["active"] = Config("settings.inactive");
		}

		$user = User::byRole()->findOrFail($id);
		$profile = $user->profile;
		if(!$profile) $profile = new UserProfiles($data["profile"]);
		if($request->hasFile('profile.avatar')) {
			if(!empty($profile->avatar))
				\Storage::delete($profile->avatar);

			$data["profile"]["avatar"] = $profile->uploadAndResizeAvatar($request->file('profile')["avatar"]);

		}
		$user->update($data);

		if(!empty($data['profile']["birthday"]))
			$data['profile']["birthday"] = \DateTime::createFromFormat(config('settings.format.date'), $data['profile']["birthday"])->format('Y-m-d');

		foreach ($data["profile"] as $nameCol => $valCol) {
			$profile->{$nameCol} = $valCol;
		}

		$user->profile()->save($profile);


		$user->roles()->detach();
		foreach ($request->roles as $role) {
			$user->assignRole($role);
		}
        toastr()->success(__('message.user.updated_success'));

		return redirect('admin/users');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 *
	 * @return void
	 */
	public function destroy($id)
	{
		User::byRole()->findOrFail($id)->delete();

        toastr()->success(__('message.user.deleted_success'));

		return redirect('admin/users');
	}

	/**
	 * Show profile
	 *
	 * @return void
	 */
	public function getProfile()
	{
		$user = \Auth::user();
		$user_roles = array_pluck($user->roles, 'name');
		$profile = $user->profile;

		if(!empty($profile->birthday))
			$profile->birthday = Carbon::parse($profile->birthday)->format(config('settings.format.date'));

		return view('admin.users.profile', compact('user', 'user_roles', 'profile'));
	}
	/**
	 * Update profile
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return void
	 */
	public function postProfile(Request $request)
	{
		$user = \Auth::user();
        $dateValidate = $request->wantsJson() ? 'date|date_format:Y-m-d' : 'date_format:"'.config('settings.format.date').'"';
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'profile.birthday' => 'nullable|'.$dateValidate,
			'profile.avatar' => 'nullable|mimes:jpeg,jpg,png',
			'email' => 'required|email|max:255|unique:users,email,' . $user->id,
			//'username' => 'required|alpha_num|min:6|max:80|unique:users,username,' . $user->id,
			'password_current' => 'required_with:password',
			'password' => 'nullable|min:6|confirmed|different:password_current',
		]);
		$validator->after(function ($validator) use ($user, $request) {
			if( !empty($request->password) ){
				$check = auth()->validate([
					'email'    => $user->email,
					'password' => $request->password_current
				]);
				if (!$check):
					$validator->errors()->add('password_current',
						'Mật khẩu hiện tại không đúng, vui lòng thử lại.');
				endif;
			}
		});
		if ($validator->fails()) {
			return redirect('profile')->withErrors($validator)->withInput();
		}
		$data = $request->except(['password', 'username']);
		if ($request->has('password') && !empty($request->password)) {
			$data['password'] = bcrypt($request->password);
		}
		$profile = $user->profile;
		if(!$profile) $profile = new UserProfiles($data["profile"]);
		if(!empty($request["profile"]["birthday"])){
		    if (!$request->wantsJson()){
                $data["profile"]["birthday"] = \DateTime::createFromFormat(config('settings.format.date'), $request["profile"]["birthday"])->format('Y-m-d');
            }
        }

		if($request->hasFile('profile.avatar')) {
			if(!empty($profile->avatar))
				\Storage::delete($profile->avatar);

			$data["profile"]["avatar"] = $profile->uploadAndResizeAvatar($request->file('profile')["avatar"]);

		}
		$user->update($data);

		foreach ($data["profile"] as $nameCol => $valCol) {
			$profile->{$nameCol} = $valCol;
		}

		$user->profile()->save($profile);

		if ($request->wantsJson()){
            return response()->json([
                'message' => __('message.user.profile_updated'),
                'user_id' => $user->id,
            ]);
        }

        toastr()->success(__('message.user.updated_success'));

        return redirect('admin/users');
	}

	/**
	 * Change password API
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function changePassword(Request $request){
		$this->validate($request, [
			'password_current' => [
				'required',
				function($attribute, $value, $fail) use ($request) {
					if (!empty($value) && !\Hash::check($value, \Auth::user()->getAuthPassword())) {
						return $fail('Mật khẩu hiện tại không đúng, vui lòng thử lại.');
					}
				}
			],
			'password' => 'required|min:6|confirmed|different:password_current',
		]);

		$data['password'] = bcrypt($request->password);
		$user = \Auth::user();
		$user->update($data);
		$user->revokeAllTokens();

		return response()->json(['message'=>'ok']);
	}
}
