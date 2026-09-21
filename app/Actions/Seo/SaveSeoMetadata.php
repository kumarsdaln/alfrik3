<?php

namespace App\Actions\Seo;

use Illuminate\Database\Eloquent\Model;

class SaveSeoMetadata
{
    public function handle(
        Model $model,
        array $data,
    ) {
        abort_unless(
            method_exists($model, 'seo'),
            404
        );

        return $model->seo()->updateOrCreate(
            [
                'locale' => $data['locale'],
            ],
            $data
        );
    }
}