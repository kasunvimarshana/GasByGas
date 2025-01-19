<?php

namespace App\Traits;

use App\Scopes\CompanyScope;

trait HasCompanyScope {
    protected static function getCompanyScopeColumn(): string {
        return 'company_id';
    }

    // Boot the trait and add the global scope to the model
    protected static function bootHasCompanyScope() {
        $scope_column = static::getCompanyScopeColumn();
        static::addGlobalScope(new CompanyScope($scope_column));
    }
}
