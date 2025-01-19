<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class CustomQueryBuilder extends Builder {
    public function filterBy(string $column, mixed $value): self {
        // (string $column, string $operator, mixed $value)
        return $this->where($column, $value);
    }

    public function forCurrentCompany(string $column = 'company_id'): self {
        $userCompany = optional(auth()->user())->company;
        $userCompanyId = optional($userCompany)->id;
        return $userCompanyId ? $this->filterBy($column, $userCompanyId) : $this;
    }

    // public function orderBy(string $column, string $direction = 'asc') {
    //     return $this->orderBy($column, $direction);
    // }
}
