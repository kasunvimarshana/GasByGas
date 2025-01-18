<?php

namespace App\Services\NavigationService\Contracts;

interface NavigationServiceInterface {
    public function getItems(): array;
    public function addCustom(string $label, string $url): self;
    public function userHasPermission($item): bool;
    public function userHasPermissionOrAnyChild($item): bool;
    public function isRouteActive($item): bool;
    public function hasActiveChild($item): bool;
}
