<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    /**
     * 保存一对多关系
     * @param Model $related
     * @param array $models
     * @return array|bool
     */
    public function saveMany(Model $related, $models = [])
    {
        if (empty($models)) {
            return false;
        }
        $inserts = [];
        foreach ($models as $k => $v) {
            $foreignKey = $this->getForeignKey();
            $v->attributes[$foreignKey] = $this->id;
            $v->attributes['created_at'] = Carbon::now();
            $v->attributes['updated_at'] = Carbon::now();
            $inserts[] = $v->attributes;
        }
        return $related::insert($inserts) ? true : false;
    }
}
