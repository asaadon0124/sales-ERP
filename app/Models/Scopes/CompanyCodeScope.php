<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyCodeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();
        if ($user)
        {
            // $builder->where('company_code', $user->company_code);
            $builder->where($model->getTable() . '.company_code', $user->company_code);

        }
        // $builder->where('company_code',auth()->user()->company_code);
    }
}
