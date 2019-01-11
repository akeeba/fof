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

namespace FOF40\Timer;

use FOF40\Tests\Helpers\Timer\FakeTimer;

global $fofTest_FakeTimer_Active;
$fofTest_FakeTimer_Active = false;

function microtime($get_as_float = null)
{
	return FakeTimer::microtime($get_as_float);
}
