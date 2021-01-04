<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */


class LanguageDataprovider
{
	public static function getTestOnBeforeBuildQuery()
	{
		$data[] = [
			'input' => [
				'langField' => null,
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => false,
				],
			],
			'check' => [
				'case'      => "Model hasn't a language field",
				'blacklist' => 1,
				'where'     => [],
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'language',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => null,
				],
			],
			'check' => [
				'case'      => "Application doesn't have the language filter method",
				'blacklist' => 1,
				'where'     => [],
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'language',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => false,
				],
			],
			'check' => [
				'case'      => "Application has the language filter method, but return false",
				'blacklist' => 1,
				'where'     => [],
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'language',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => true,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'      => "Plugin option set to remove the prefix",
				'blacklist' => 1,
				'where'     => ["`language` IN('*', 'en-GB')"],
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'language',
				'input'     => [],
				'mock'      => [
					'admin'        => true,
					'removePrefix' => true,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'      => "Plugin option set to remove the prefix, we're in backend",
				'blacklist' => 0,
				'where'     => ["`language` IN('*', 'en-GB')"],
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'language',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'      => "Plugin option not set to remove the prefix, language is not set inside app input",
				'blacklist' => 1,
				'where'     => ["`language` IN('*')"],
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'language',
				'input'     => [
					'language' => 'it-IT',
				],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'      => "Plugin option not set to remove the prefix, language is set inside app input",
				'blacklist' => 1,
				'where'     => ["`language` IN('*', 'it-IT')"],
			],
		];

		return $data;
	}

	public static function getTestOnAfterLoad()
	{
		$data[] = [
			'input' => [
				'langField' => null,
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => false,
				],
			],
			'check' => [
				'case'  => "Model hasn't a language field",
				'reset' => 0,
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'it-IT',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => null,
				],
			],
			'check' => [
				'case'  => "Application doens't have a language filter method",
				'reset' => 0,
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'it-IT',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => false,
				],
			],
			'check' => [
				'case'  => "Application has a language filter method and returns false",
				'reset' => 0,
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'it-IT',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => true,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'  => "Plugin option set to remove the prefix, model language is not the same of the site",
				'reset' => 1,
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'en-GB',
				'input'     => [],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => true,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'  => "Plugin option set to remove the prefix, model language is the same of the site",
				'reset' => 0,
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'it-IT',
				'input'     => [
					'language' => 'en-GB',
				],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'  => "Plugin option not set to remove the prefix, language is set inside app input, model language is not the same of the site",
				'reset' => 1,
			],
		];

		$data[] = [
			'input' => [
				'langField' => 'en-GB',
				'input'     => [
					'language' => 'en-GB',
				],
				'mock'      => [
					'admin'        => false,
					'removePrefix' => false,
					'langFilter'   => true,
				],
			],
			'check' => [
				'case'  => "Plugin option not set to remove the prefix, language is set inside app input, model language is the same of the site",
				'reset' => 0,
			],
		];

		return $data;
	}
}
