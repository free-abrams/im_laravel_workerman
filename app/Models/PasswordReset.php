<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @TableName password_resets
 * @Describe 用户token模型
 * @CreateTime 2019-12-12 15:28:25
 * @Sign 小肥柴
 */
class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $fillable = [
        'user_id',
        'token',
        'time_out',
    ];
}
