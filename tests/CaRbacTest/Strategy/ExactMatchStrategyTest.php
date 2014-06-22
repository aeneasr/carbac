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

namespace CaRbacTest\Strategy;

use CaRbac\Strategy\ExactMatchStrategy;
use CaRbacTest\Asset\ParameterAsset;
use CaRbacTest\Asset\ParameterProviderAsset;
use CaRbacTest\Asset\PermissionAsset;
use PHPUnit_Framework_TestCase as TestCase;

class ExactMatchStrategyTest extends TestCase
{
    public function testAreMatchingIsTrue()
    {
        $match      = new ExactMatchStrategy();
        $parameter  = new ParameterAsset('foo', 'bar');
        $permission = new PermissionAsset('create');
        $provider   = new ParameterProviderAsset([$parameter]);

        $permission->addParameter($parameter);

        $this->assertTrue($match->areMatching($permission, $provider));
    }

    public function testAreMatchingIsFalse()
    {
        $match      = new ExactMatchStrategy();
        $parameter  = new ParameterAsset('foo', 'bar');
        $permission = new PermissionAsset('create');
        $provider   = new ParameterProviderAsset([]);

        $permission->addParameter($parameter);

        $this->assertTrue($match->areMatching($permission, $provider));
    }

    public function testAreMatchingIsFalseBecausePermissionLacksParameter()
    {
        $match                  = new ExactMatchStrategy();
        $parameter              = new ParameterAsset('foo', 'bar');
        $parameterForPermission = new ParameterAsset('foo', 'acme');
        $permission             = new PermissionAsset('create');
        $provider               = new ParameterProviderAsset([$parameter]);

        $permission->addParameter($parameterForPermission);

        $this->assertTrue($match->areMatching($permission, $provider));
    }
}
