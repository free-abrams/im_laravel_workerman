<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHasFollow extends BaseModel
{
    use HasFactory;

	protected $fillable = [
		'user_id',
		'follow_id',
		'nickname',
		'cover',
		'pinyin',
		'address_string',
		'type',
		'is_trade',
        'status'
	];


	protected function serializeDate(\DateTimeInterface $date)
	{
		return $date->getTimestamp();
	}

    public function phone()
    {
    	return $this->hasMany(FollowMobile::class);
    }

    public function address()
    {
    	return $this->hasMany(FollowAddress::class);
    }

    public function group()
    {
    	return $this->belongsToMany(UserGroup::class, 'follow_has_groups')->withTimestamps();
    }

    public function follow()
    {
    	return $this->belongsTo(User::class, 'follow_id', 'id');
    }

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function record()
    {
    	return $this->hasMany(RecordPay::class);
    }

    public function order_record()
    {
    	return $this->hasOne(FollowOrderRecord::class, 'user_has_follow_id');
    }

    public function getNicknameAttribute($value)
    {
        if(empty($value) && $value == '' && isset($this->attributes['follow_id']) && $this->attributes['follow_id'] > 0){
            $values =User::query()->where('id','=',$this->attributes['follow_id'])->value('name');
        }else{
            $values = $value;
        }
        $this->attributes['name'] = $values;
        return $values;
    }

    public function getAddressStringAttribute($value)
    {
        return json_decode($value, true);
    }
}
