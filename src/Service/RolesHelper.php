<?php

namespace App\Service;

use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class RolesHelper
{
    /**
     * @var RoleHierarchyInterface $rolesHierarchy
     */
    private $rolesHierarchy;
    /**
     * @var [] $roles
     */
    private $roles = [];

    /**
     * RolesHelper constructor.
     *
     * @param RoleHierarchyInterface $rolesHierarchy
     */
    public function __construct(RoleHierarchyInterface $rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        if ($this->roles) {
            return $this->roles;
        }

        array_walk_recursive($this->rolesHierarchy, function ($val) {
            $this->roles[] = $val;
        });

        return $this->roles = array_unique($this->roles);
    }
}