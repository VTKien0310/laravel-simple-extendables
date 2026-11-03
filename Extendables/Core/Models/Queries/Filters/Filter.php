<?php

namespace App\Extendables\Core\Models\Queries\Filters;

use App\Extendables\Core\Http\Request\States\QueryString\FilterCondition;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

abstract class Filter
{
    /**
     * No definite usage. Feel free to use it as you want to customize the filter behavior
     *
     * @var string|null
     */
    protected ?string $targetColumn = null;

    /**
     * @param  string|null  $targetColumn
     * @return Filter
     */
    public function setTargetColumn(?string $targetColumn): Filter
    {
        $this->targetColumn = $targetColumn;
        return $this;
    }

    /**
     * The column to filter on: the target column when set, else the filter's field (qualified for Eloquent builders).
     *
     * @param  EloquentBuilder|Builder  $builder
     * @param  FilterCondition  $filterCondition
     * @return string
     */
    protected function resolveColumn(EloquentBuilder|Builder $builder, FilterCondition $filterCondition): string
    {
        $column = $this->targetColumn ?: $filterCondition->field;

        return $builder instanceof EloquentBuilder ? $builder->qualifyColumn($column) : $column;
    }

    /**
     * @param  EloquentBuilder|Builder  $builder
     * @param  FilterCondition  $filterCondition
     * @return Builder|EloquentBuilder
     */
    abstract function handle(
        EloquentBuilder|Builder $builder,
        FilterCondition $filterCondition
    ): Builder|EloquentBuilder;
}
