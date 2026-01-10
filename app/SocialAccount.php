<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function createOrGetUser($social, $social_user){
        $account = SocialAccount::where('provider', $social)->where('provider_user_id', $social_user->getId())->first();
        if ($account){
            return $account->user;
        }else{
            $user = User::where("email", $social_user->getEmail())->first();
            \DB::transaction(function () use ($social, $social_user, &$user){
                if (!$user){
                    $user = User::create([
                        'email' => $social_user->getEmail(),
                        'name' => $social_user->getName() ?? $social_user->getNickname(),
                    ]);
                    $user->profile()->create([
                        'avatar' => $social_user->getAvatar(),
                    ]);
                    // tao quyen
                    $role_company = Role::where('name', "user")->first();
                    $user->roles()->attach($role_company);

                    SocialAccount::create([
                        'provider_user_id' => $social_user->getId(),
                        'provider' => $social,
                        'user_id' => $user->id
                    ]);

                } else {
                    $user->update([
                        'email' => $social_user->getEmail(),
                        'name' => $social_user->getName() ?? $social_user->getNickname(),
                    ]);
                    $user->profile->avatar = $social_user->getAvatar();
                    $user->profile->save();

                    $account = SocialAccount::where('provider', $social)->where('user_id', $user->id)->first();
                    if($account){
                        SocialAccount::where('provider', $social)->where('user_id', $user->id)->update([
                            'provider_user_id' => $social_user->getId(),
                        ]);
                    }else{
                        SocialAccount::create([
                            'provider_user_id' => $social_user->getId(),
                            'provider' => $social,
                            'user_id' => $user->id
                        ]);
                    }
                }
            });
            return $user;
        }
    }
}
