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

namespace CaRbacTest;

use CaRbac\CaRbac;
use CaRbacTest\Asset\ParameterAsset;
use CaRbacTest\Asset\ParameterProviderAsset;
use CaRbacTest\Asset\PermissionAsset;
use CaRbacTest\Asset\RoleAsset;
use PHPUnit_Framework_TestCase as TestCase;

class CaRbacTest extends TestCase
{
    public function testReturnTrueWhenRoleHasPermission()
    {
        $carbac     = new CaRbac();
        $role       = new RoleAsset('admin');
        $permission = new PermissionAsset('create');

        $role->addPermission($permission);

        $this->assertTrue($carbac->isGranted($role, $permission));
    }

    public function testReturnFalseWhenRoleHasNotPermission()
    {
        $carbac     = new CaRbac();
        $role       = new RoleAsset('admin');
        $permission = new PermissionAsset('create');

        $this->assertFalse($carbac->isGranted($role, $permission));
    }

    public function testReturnTrueWhenRoleHasPermissionAndParameters()
    {
        $carbac            = new CaRbac();
        $role              = new RoleAsset('admin');
        $parameter         = new ParameterAsset('foo', 'bar');
        $permission        = new PermissionAsset('create');
        $parameterProvider = new ParameterProviderAsset([$parameter]);

        $permission->addParameter($parameter);
        $role->addPermission($permission);

        $this->assertTrue($carbac->isGranted($role, $permission, $parameterProvider));
    }

    public function testReturnFalseWhenRoleHasPermissionButNotParameter()
    {
        $carbac            = new CaRbac();
        $role              = new RoleAsset('admin');
        $parameter         = new ParameterAsset('foo', 'bar');
        $permission        = new PermissionAsset('create');
        $parameterProvider = new ParameterProviderAsset([$parameter]);

        $role->addPermission($permission);

        $this->assertTrue($carbac->isGranted($role, $permission, $parameterProvider));
    }
}
