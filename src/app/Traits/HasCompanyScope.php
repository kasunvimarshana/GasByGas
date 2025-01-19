<?php

namespace App\Traits;

use App\Scopes\CompanyScope;

trait HasCompanyScope {
    protected static function getCompanyScopeColumn(): string {
        return 'company_id';
    }

    protected static function bootHasCompanyScope() {
        $scope_column = static::getCompanyScopeColumn();
        static::addGlobalScope(new CompanyScope($scope_column));
    }
}
