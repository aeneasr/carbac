<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace CaRbac;

use CaRbac\Parameter\ParameterProviderInterface;
use CaRbac\Permission\PermissionInterface;
use CaRbac\Role\RoleInterface;
use CaRbac\Strategy\ExactMatchStrategy;
use CaRbac\Strategy\MatchStrategyInterface;
use Rbac\Traversal\Strategy\GeneratorStrategy;
use Rbac\Traversal\Strategy\RecursiveRoleIteratorStrategy;
use Rbac\Traversal\Strategy\TraversalStrategyInterface;
use Traversable;

class CaRbac
{
    /**
     * @var TraversalStrategyInterface
     */
    protected $traversalStrategy;

    /**
     * @param MatchStrategyInterface|null     $matchStrategy
     * @param TraversalStrategyInterface|null $strategy
     */
    public function __construct(
        MatchStrategyInterface $matchStrategy = null,
        TraversalStrategyInterface $strategy = null
    ) {
        if (null !== $strategy) {
            $this->traversalStrategy = $strategy;
        } elseif (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            $this->traversalStrategy = new GeneratorStrategy();
        } else {
            $this->traversalStrategy = new RecursiveRoleIteratorStrategy();
        }

        if (null === $matchStrategy) {
            $matchStrategy = new ExactMatchStrategy();
        }

        $this->matchStrategy = $matchStrategy;
    }

    /**
     * Determines if access is granted by checking the roles for permission.
     *
     * @param RoleInterface|RoleInterface[]|Traversable $roles
     * @param PermissionInterface|string                $permission
     * @param ParameterProviderInterface                $parameterProvider
     * @return bool
     */
    public function isGranted($roles, $permission, ParameterProviderInterface $parameterProvider = null)
    {
        $permission = (string)$permission;

        if ($roles instanceof RoleInterface) {
            $roles = [$roles];
        }

        $iterator = $this->traversalStrategy->getRolesIterator($roles);

        foreach ($iterator as $role) {
            /* @var RoleInterface $role */
            if (null === $parameterProvider) {
                if ($role->hasPermission($permission)) {
                    return true;
                }
            } else {
                $permissions = $role->getPermissions($permission);
                foreach ($permissions as $permission) {
                    if ($this->matchStrategy->areMatching($permission, $parameterProvider)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Get the strategy.
     *
     * @return TraversalStrategyInterface
     */
    public function getTraversalStrategy()
    {
        return $this->traversalStrategy;
    }
}
