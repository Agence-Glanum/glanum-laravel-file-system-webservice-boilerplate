<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder as BaseBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $macro = function (int $maxResults = null, int $defaultSize = null, string $type = 'paginate') {
            $numberParameter = 'number';
            $cursorParameter = 'cursor';
            $sizeParameter = 'size';
            $paginationParameter = 'page';

            $size = (int) request()->input($paginationParameter.'.'.$sizeParameter, $defaultSize);
            $cursor = (string) request()->input($paginationParameter.'.'.$cursorParameter);

            if ($size <= 0) {
                $size = $defaultSize;
            }

            if ($size > $maxResults) {
                $size = $maxResults;
            }

            return $type === 'cursorPaginate'
                ? $this->{$type}($size, ['*'], $paginationParameter.'['.$cursorParameter.']', $cursor)
                    ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$cursorParameter))
                : $this
                    ->{$type}($size, ['*'], $paginationParameter.'.'.$numberParameter)
                    ->setPageName($paginationParameter.'['.$numberParameter.']')
                    ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$numberParameter));
        };

        EloquentBuilder::macro('jsonPaginate', $macro);
        BaseBuilder::macro(config('jsonPaginate'), $macro);
        BelongsToMany::macro(config('jsonPaginate'), $macro);
        HasManyThrough::macro(config('jsonPaginate'), $macro);
    }
}
