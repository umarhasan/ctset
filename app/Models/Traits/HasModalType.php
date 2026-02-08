<?php

namespace App\Models\Traits;

use App\Models\ModalType;

trait HasModalType
{
    protected static function bootHasModalType()
    {
        static::creating(function ($model) {
            if (empty($model->modal_type_id)) {
                $tableName = $model->getTable();
                $modal = ModalType::where('table_name', $tableName)->first();
                $model->modal_type_id = $modal ? $modal->id : null;
            }
        });
    }
}