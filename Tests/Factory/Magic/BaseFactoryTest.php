<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF40\Tests\Factory\Magic;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

/**
 * @covers      FOF40\Factory\Magic\BaseFactory::<protected>
 * @covers      FOF40\Factory\Magic\BaseFactory::<private>
 * @package     FOF40\Tests\Factory
 */
class BaseFactoryTest extends FOFTestCase
{
    /**
     * @covers      FOF40\Factory\Magic\BaseFactory::__construct
     */
    public function test__construct()
    {
        $container = static::$container;

        $factory = $this->getMockForAbstractClass('FOF40\Factory\Magic\BaseFactory', array($container));

        $this->assertSame($container, ReflectionHelper::getValue($factory, 'container'));
    }
}
