<?php

namespace Modules\Management\UserManagement\User\Database\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Model extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    use SoftDeletes;

    protected $table = "users";
    protected $guarded = [];
    protected $hidden = ['password'];




    public static $roleModel = \Modules\Management\UserManagement\Role\Database\Models\Model::class;
    static $UserAddressModel = \Modules\Management\UserManagement\User\Database\Models\UserAddressModel::class;
    static $UserLogModel = \Modules\Management\UserManagement\User\Database\Models\UserLogModel::class;
    static $UserSocialLinkModel = \Modules\Management\UserManagement\User\Database\Models\UserSocialLinkModel::class;

    protected static function booted()
    {
        static::created(function ($data) {
            $random_no = random_int(100, 999) . $data->id . random_int(100, 999);
            $slug = $data->title . " " . $random_no;
            $data->slug = Str::slug($slug); //use Illuminate\Support\Str;
            if (strlen($data->slug) > 50) {
                $data->slug = substr($data->slug, strlen($data->slug) - 50, strlen($data->slug));
            }
            if (auth()->check()) {
                $data->creator = auth()->user()->id;
            }
            $data->save();
        });
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeInactive($q)
    {
        return $q->where('status', 'inactive');
    }
    public function scopeTrased($q)
    {
        return $q->onlyTrashed();
    }

    public function role()
    {
        return $this->belongsTo(self::$roleModel);
    }

    public function address()
    {
        return $this->hasOne(self::$UserAddressModel, 'user_id', 'id');
    }
    public function logs()
    {
        return $this->hasMany(self::$UserLogModel, 'user_id', 'id');
    }
    public function socialLinks()
    {
        return $this->hasMany(self::$UserSocialLinkModel, 'user_id', 'id');
    }
    public function social_links()
    {
        return $this->hasMany(self::$UserSocialLinkModel, 'user_id', 'id');
    }
}
