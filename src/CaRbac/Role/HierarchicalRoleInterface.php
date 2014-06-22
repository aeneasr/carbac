<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

namespace CaRbac\Role;

use Traversable;

/**
 * Interface for a hierarchical role
 * A hierarchical role is a role that can have children.
 */
interface HierarchicalRoleInterface extends RoleInterface, \Rbac\Role\HierarchicalRoleInterface
{
    /**
     * Check if the role has children
     *
     * @return bool
     */
    public function hasChildren();

    /**
     * Get child roles
     *
     * @return array|RoleInterface[]|Traversable
     */
    public function getChildren();
}
