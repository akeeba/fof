<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Utils;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Utils\Buffer;

/**
 * Class BufferTest
 *
 * @package FOF40\Tests\Utils
 *
 * @coversDefaultClass FOF40\Utils\Buffer
 */
class BufferTest extends FOFTestCase
{
	/**
	 * @group   Buffer
	 * @covers  FOF40\Utils\Buffer::stream_open
	 */
	public function testStreamOpen()
	{
		$buffer = new Buffer();

		$dummy  = null;
		$return = $buffer->stream_open('buffer://path/to/some/file', 'w', null, $dummy);

		$this->assertTrue($return);

		$this->assertEquals(
			'path/to/some/file',
			$buffer->name
		);

		$this->assertArrayHasKey(
			'path/to/some/file',
			Buffer::$buffers
		);

		$this->assertNull(
			Buffer::$buffers['path/to/some/file']
		);

		$this->assertEquals(
			0,
			$buffer->position
		);
	}

	/**
	 * @group   Buffer
	 * @covers  \FOF40\Utils\Buffer::stream_write
	 */
	public function testStreamWrite()
	{
		$buffer  = $this->buildBuffer();
		$written = $buffer->stream_write('0123456789');

		$this->assertEquals(
			10,
			$written
		);

		$this->assertEquals(
			10,
			$buffer->position
		);

		$this->assertEquals(
			'0123456789',
			Buffer::$buffers['path/to/some/file']
		);

		$buffer->position = 0;

		$written = $buffer->stream_write('ABCDE');

		$this->assertEquals(
			5,
			$written
		);

		$this->assertEquals(
			5,
			$buffer->position
		);

		$this->assertEquals(
			'ABCDE56789',
			Buffer::$buffers['path/to/some/file']
		);
	}

	/**
	 * @group   Buffer
	 * @covers  \FOF40\Utils\Buffer::stream_read
	 */
	public function testStreamRead()
	{
		$buffer = $this->buildBuffer();
		$buffer->stream_write('0123456789');
		$buffer->position = 0;
		$buffer->stream_write('ABCDE');

		$buffer->position = 0;

		$return = $buffer->stream_read(5);

		$this->assertEquals(
			'ABCDE',
			$return
		);

		$this->assertEquals(
			5,
			$buffer->position
		);

		$buffer->position = 1;

		$return = $buffer->stream_read(5);

		$this->assertEquals(
			6,
			$buffer->position
		);

		$this->assertEquals(
			'BCDE5',
			$return
		);

		$buffer->position = 0;

		$return = $buffer->stream_read(1024);

		$this->assertEquals(
			10,
			$buffer->position
		);

		$this->assertEquals(
			'ABCDE56789',
			$return
		);
	}

	/**
	 * @group   Buffer
	 * @covers  FOF40\Utils\Buffer::stream_tell
	 */
	public function testStreamTell()
	{
		$buffer = $this->buildBuffer();
		$buffer->stream_write('0123456789');
		$buffer->position = 0;
		$buffer->stream_write('ABCDE');

		$positions = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

		foreach ($positions as $pos)
		{
			$buffer->position = $pos;

			$ret = $buffer->stream_tell();

			$this->assertEquals($pos, $ret);
		}
	}

	/**
	 * @group   Buffer
	 * @covers  FOF40\Utils\Buffer::stream_eof
	 */
	public function testStreamEof()
	{
		$buffer = $this->buildBuffer();
		$buffer->stream_write('0123456789');
		$buffer->position = 0;
		$buffer->stream_write('ABCDE');

		$data = [
			[0, false],
			[5, false],
			[10, true],
		];

		foreach ($data as $datum)
		{
			list($pos, $expected) = $datum;

			$buffer->position = $pos;

			$ret = $buffer->stream_eof();

			$this->assertEquals($expected, $ret);
		}
	}

	/**
	 * @group   Buffer
	 * @covers  FOF40\Utils\Buffer::stream_seek
	 */
	public function testStreamSeek()
	{
		$buffer = $this->buildBuffer();
		$buffer->stream_write('0123456789');
		$buffer->position = 0;
		$buffer->stream_write('ABCDE');

		$data = [
			[0, SEEK_SET, 0, true],
			[5, SEEK_SET, 5, true],
			[100, SEEK_SET, 5, false],
			[2, SEEK_CUR, 7, true],
			[5, SEEK_CUR, 12, true],
			[-6, SEEK_CUR, 12, false],
			[5, SEEK_SET, 5, true],
			[-5, SEEK_END, 5, true],
			[-10, SEEK_END, 0, true],
			[0, SEEK_END, 10, true],
			[-20, SEEK_END, 10, false],
		];

		foreach ($data as $point)
		{
			list($offset, $whence, $expectedPos, $expectedResult) = $point;

			$result = $buffer->stream_seek($offset, $whence);

			$this->assertEquals($expectedResult, $result);
			$this->assertEquals($expectedPos, $buffer->position);
		}
	}

	/**
	 * @return Buffer
	 */
	protected function buildBuffer()
	{
		$buffer = new Buffer();

		$dummy = null;

		$buffer->stream_open('buffer://path/to/some/file', 'w', null, $dummy);

		return $buffer;
	}
}
