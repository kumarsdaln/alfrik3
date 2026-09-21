<?php

namespace App\Support;

use App\Models\Interview\Interview;
use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineArticle;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Database\Eloquent\Model;

final class ModelResolver
{
    /**
     * Models that can be managed by generic admin modules.
     */
    private const MODELS = [
        //Interview
        'interview' => Interview::class,

        // Magazine
        'magazine' => Magazine::class,
        'magazine-issue' => MagazineIssue::class,
        'magazine-article' => MagazineArticle::class,
    ];

    public static function class(string $type): string
    {
        return self::MODELS[$type] ?? abort(404);
    }

    public static function resolve(
        string $type,
        int|string $id,
    ): Model 
    {
        $modelClass = self::class($type);

        return $modelClass::findOrFail($id);
    }

    public static function exists(string $type): bool
    {
        return isset(self::MODELS[$type]);
    }
}