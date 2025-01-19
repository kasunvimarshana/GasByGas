<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CompanyScope implements Scope {
    protected string $scope_column; // foreign_key

    public function __construct(string $scope_column = 'company_id') {
        $this->scope_column = $scope_column;
    }

    /**
     * Apply the scope to the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model) {
        if ($this->shouldApplyScope()) {
            $userCompanyId = $this->getUserCompanyId();
            if ($userCompanyId) {
                $builder->where($this->scope_column, $userCompanyId);
            }
        }
    }

    /**
     * Determine if the scope should be applied.
     *
     * @return bool
     */
    protected function shouldApplyScope(): bool {
        return auth()->check();
    }

    /**
     * Get the company ID of the authenticated user.
     *
     * @return int|null
     */
    protected function getUserCompanyId(): ?int {
        return optional(auth()->user()->company)->id;
    }
}
