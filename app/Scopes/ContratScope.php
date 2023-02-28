<?php

namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class ContratScope implements Scope {

    public function apply(Builder $builder, Model $model)
    {
            $builder->where( $model->getTable() . '.compagnie_id', Auth::user()->compagnie_id );
    }
}
