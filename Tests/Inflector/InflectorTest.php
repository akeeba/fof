<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace FOF40\Tests\Inflector;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestContainer;

/**
 * Test class for FOF40\Inflector\Inflector.
 *
 * @since  1.0
 */
class InflectorTest extends FOFTestCase
{
	/**
	 * Returns test data for pluralize()
	 *
	 * @return array
	 */
	public function getTestPluralizeData()
	{
		return [
			["move", "moves", "Pluralise: Move"],
			["moves", "moves", "Pluralise: Moves"],
			["sex", "sexes", "Pluralise: Sex"],
			["sexes", "sexes", "Pluralise: Sexes"],
			["child", "children", "Pluralise: Child"],
			["children", "children", "Pluralise: Children"],
			["woman", "women", "Pluralisation of words in -an not honoured"],
			["women", "women", "Should return the same as it's already a plural (words in -an)"],
			["foot", "feet", "Pluralise: Foot"],
			["feet", "feet", "Pluralise: Feet"],
			["person", "people", "Pluralise: Person"],
			["people", "people", "Pluralise: People"],
			["taxon", "taxa", "Pluralise: Taxon"],
			["taxa", "taxa", "Pluralise: Taxa"],
			["quiz", "quizzes", "Pluralise: Quiz"],
			["quizzes", "quizzes", "Pluralise: Quizzes"],
			["ox", "oxen", "Pluralise: Ox"],
			["oxen", "oxen", "Pluralise: Oxen"],
			["mouse", "mice", "Pluralise: Mouse"],
			["mice", "mice", "Pluralise: Mice"],
			["matrix", "matrices", "Pluralise: Matrix"],
			["matrices", "matrices", "Pluralise: Matrices"],
			["vertex", "vertices", "Pluralise: Vertex"],
			["vertices", "vertices", "Pluralise: Vertices"],
			["index", "indices", "Pluralise: Index"],
			["indices", "indices", "Pluralise: Indices"],
			["suffix", "suffices", "Pluralise: Suffix"],
			["suffices", "suffices", "Pluralise: Suffices"],
			["codex", "codices", "Pluralise: Codex"],
			["codices", "codices", "Pluralise: Codices"],
			["onyx", "onyxes", "Pluralise: onyx"],
			["onyxes", "onyxes", "Pluralise: onyxes"],
			["leech", "leeches", "Pluralise: Leech"],
			["leeches", "leeches", "Pluralise: Leeches"],
			["glass", "glasses", "Pluralise: Glass"],
			["glasses", "glasses", "Pluralise: Glasses"],
			["mesh", "meshes", "Pluralise: Mesh"],
			["meshes", "meshes", "Pluralise: Meshes"],
			["soliloquy", "soliloquies", "Pluralise: Soliloquy"],
			["soliloquies", "soliloquies", "Pluralise: Soliloquies"],
			["baby", "babies", "Pluralise: Baby"],
			["babies", "babies", "Pluralise: Babies"],
			["elf", "elves", "Pluralise: Elf"],
			["elves", "elves", "Pluralise: Elves"],
			["life", "lives", "Pluralise: Life"],
			["lives", "lives", "Pluralise: Lives"],
			["antithesis", "antitheses", "Pluralise: Antitheses"],
			["antitheses", "antitheses", "Pluralise: Antitheses"],
			["consortium", "consortia", "Pluralise: consortium"],
			["consortia", "consortia", "Pluralise: consortia"],
			["addendum", "addenda", "Pluralise: addendum"],
			["addenda", "addenda", "Pluralise: addenda"],
			["alumna", "alumnae", "Pluralise: alumna"],
			["alumnae", "alumnae", "Pluralise: alumnae"],
			["formula", "formulae", "Pluralise: formula"],
			["formulae", "formulae", "Pluralise: formulae"],
			["buffalo", "buffaloes", "Pluralise: buffalo"],
			["buffaloes", "buffaloes", "Pluralise: buffaloes"],
			["tomato", "tomatoes", "Pluralise: tomato"],
			["tomatoes", "tomatoes", "Pluralise: tomatoes"],
			["hero", "heroes", "Pluralise: hero"],
			["heroes", "heroes", "Pluralise: heroes"],
			["bus", "buses", "Pluralise: bus"],
			["buses", "buses", "Pluralise: buses"],
			["alias", "aliases", "Pluralise: alias"],
			["aliases", "aliases", "Pluralise: aliases"],
			["octopus", "octopi", "Pluralise: octopus"],
			["octopi", "octopi", "Pluralise: octopi"],
			["virus", "viri", "Pluralise: virus"],
			["viri", "viri", "Pluralise: viri"],
			["genus", "genera", "Pluralise: genus"],
			["genera", "genera", "Pluralise: genera"],
			["axis", "axes", "Pluralise: axis"],
			["axes", "axes", "Pluralise: axes"],
			["testis", "testes", "Pluralise: testis"],
			["testes", "testes", "Pluralise: testes"],

			["dwarf", "dwarves", "Pluralise: Dwarf"],
			["dwarves", "dwarves", "Pluralise: Dwarves"],
			["guy", "guys", "Pluralise: Guy"],
			["guy", "guys", "Pluralise: Guy"],
			["relief", "reliefs", "Pluralise: Relief"],
			["reliefs", "reliefs", "Pluralise: Reliefs"],

			["aircraft", "aircraft", "Pluralise: aircraft (special)"],
			["cannon", "cannon", "Pluralise: cannon (special)"],
			["deer", "deer", "Pluralise: deer (special)"],
			["equipment", "equipment", "Pluralise: equipment (special)"],
			["fish", "fish", "Pluralise: Fish (special)"],
			["information", "information", "Pluralise: information (special)"],
			["money", "money", "Pluralise: money (special)"],
			["moose", "moose", "Pluralise: moose (special)"],
			["rice", "rice", "Pluralise: rice (special)"],
			["series", "series", "Pluralise: series (special)"],
			["sheep", "sheep", "Pluralise: sheep (special)"],
			["species", "species", "Pluralise: species (special)"],
			["swine", "swine", "Pluralise: swine (special)"],

			["word", "words", 'Should return plural'],
			["words", "words", "Should return the same as it's already a plural"],

			["cookie", "cookies", "Pluralise: cookie"],
			["cookies", "cookies", "Pluralise: cookies"],
			["database", "databases", "Pluralise: database"],
			["databases", "databases", "Pluralise: databases"],
			["crisis", "crises", "Pluralise: crisis"],
			["crises", "crises", "Pluralise: crises"],
			["shoe", "shoes", "Pluralise: shoe"],
			["shoes", "shoes", "Pluralise: shoes"],
			["backhoe", "backhoes", "Pluralise: backhoe"],
			["backhoes", "backhoes", "Pluralise: backhoes"],
			["movie", "movies", "Pluralise: movie"],
			["movies", "movies", "Pluralise: movies"],
			["vie", "vies", "Pluralise: vie"],
			["vies", "vies", "Pluralise: vies"],
			["narrative", "narratives", "Pluralise: narrative"],
			["narratives", "narratives", "Pluralise: narratives"],
			["hive", "hives", "Pluralise: hive"],
			["hives", "hives", "Pluralise: hives"],
			["analysis", "analyses", "Pluralise: analysis"],
			["analyses", "analyses", "Pluralise: analyses"],
			["basis", "bases", "Pluralise: basis"],
			["bases", "bases", "Pluralise: bases"],
			["diagnosis", "diagnoses", "Pluralise: diagnosis"],
			["diagnoses", "diagnoses", "Pluralise: diagnoses"],
			["parenthesis", "parentheses", "Pluralise: parenthesis"],
			["parentheses", "parentheses", "Pluralise: parentheses"],
			["prognosis", "prognoses", "Pluralise: prognosis"],
			["prognoses", "prognoses", "Pluralise: prognoses"],
			["synopsis", "synopses", "Pluralise: synopsis"],
			["synopses", "synopses", "Pluralise: synopses"],
			["thesis", "theses", "Pluralise: thesis"],
			["theses", "theses", "Pluralise: theses"],
			["news", "news", "Pluralise: news"],
		];
	}

	/**
	 * Returns test data for testIsSingular()
	 *
	 * @return array
	 */
	public function getTestIsSingular()
	{
		return [
			["move", true, "isSingular: Move"],
			["moves", false, "isSingular: Moves"],
			["sex", true, "isSingular: Sex"],
			["sexes", false, "isSingular: Sexes"],
			["child", true, "isSingular: Child"],
			["children", false, "isSingular: Children"],
			["woman", true, "Pluralisation of words in -an not honoured"],
			["women", false, "Should return the same as it's already a plural (words in -an)"],
			["foot", true, "isSingular: Foot"],
			["feet", false, "isSingular: Feet"],
			["person", true, "isSingular: Person"],
			["people", false, "isSingular: People"],
			["taxon", true, "isSingular: Taxon"],
			["taxa", false, "isSingular: Taxa"],
			["quiz", true, "isSingular: Quiz"],
			["quizzes", false, "isSingular: Quizzes"],
			["ox", true, "isSingular: Ox"],
			["oxen", false, "isSingular: Oxen"],
			["mouse", true, "isSingular: Mouse"],
			["mice", false, "isSingular: Mice"],
			["matrix", true, "isSingular: Matrix"],
			["matrices", false, "isSingular: Matrices"],
			["vertex", true, "isSingular: Vertex"],
			["vertices", false, "isSingular: Vertices"],
			["index", true, "isSingular: Index"],
			["indices", false, "isSingular: Indices"],
			["suffix", true, "isSingular: Suffix"],
			["suffices", false, "isSingular: Suffices"],
			["codex", true, "isSingular: Codex"],
			["codices", false, "isSingular: Codices"],
			["onyx", true, "isSingular: onyx"],
			["onyxes", false, "isSingular: onyxes"],
			["leech", true, "isSingular: Leech"],
			["leeches", false, "isSingular: Leeches"],
			["glass", true, "isSingular: Glass"],
			["glasses", false, "isSingular: Glasses"],
			["mesh", true, "isSingular: Mesh"],
			["meshes", false, "isSingular: Meshes"],
			["soliloquy", true, "isSingular: Soliloquy"],
			["soliloquies", false, "isSingular: Soliloquies"],
			["baby", true, "isSingular: Baby"],
			["babies", false, "isSingular: Babies"],
			["elf", true, "isSingular: Elf"],
			["elves", false, "isSingular: Elves"],
			["life", true, "isSingular: Life"],
			["lives", false, "isSingular: Lives"],
			["antithesis", true, "isSingular: Antitheses"],
			["antitheses", false, "isSingular: Antitheses"],
			["consortium", true, "isSingular: consortium"],
			["consortia", false, "isSingular: consortia"],
			["addendum", true, "isSingular: addendum"],
			["addenda", false, "isSingular: addenda"],
			["alumna", true, "isSingular: alumna"],
			["alumnae", false, "isSingular: alumnae"],
			["formula", true, "isSingular: formula"],
			["formulae", false, "isSingular: formulae"],
			["buffalo", true, "isSingular: buffalo"],
			["buffaloes", false, "isSingular: buffaloes"],
			["tomato", true, "isSingular: tomato"],
			["tomatoes", false, "isSingular: tomatoes"],
			["hero", true, "isSingular: hero"],
			["heroes", false, "isSingular: heroes"],
			["bus", true, "isSingular: bus"],
			["buses", false, "isSingular: buses"],
			["alias", true, "isSingular: alias"],
			["aliases", false, "isSingular: aliases"],
			["octopus", true, "isSingular: octopus"],
			["octopi", false, "isSingular: octopi"],
			["virus", true, "isSingular: virus"],
			["viri", false, "isSingular: viri"],
			["genus", true, "isSingular: genus"],
			["genera", false, "isSingular: genera"],
			["axis", true, "isSingular: axis"],
			["axes", false, "isSingular: axes"],
			["testis", true, "isSingular: testis"],
			["testes", false, "isSingular: testes"],

			["dwarf", true, "isSingular: Dwarf"],
			["dwarves", false, "isSingular: Dwarves"],
			["guy", true, "isSingular: Guy"],
			["guys", false, "isSingular: Guys"],
			["relief", true, "isSingular: Relief"],
			["reliefs", false, "isSingular: Reliefs"],

			["aircraft", true, "isSingular: aircraft (special)"],
			["cannon", true, "isSingular: cannon (special)"],
			["deer", true, "isSingular: deer (special)"],
			["equipment", true, "isSingular: equipment (special)"],
			["fish", true, "isSingular: Fish (special)"],
			["information", true, "isSingular: information (special)"],
			["money", true, "isSingular: money (special)"],
			["moose", true, "isSingular: moose (special)"],
			["rice", true, "isSingular: rice (special)"],
			["series", true, "isSingular: series (special)"],
			["sheep", true, "isSingular: sheep (special)"],
			["species", true, "isSingular: species (special)"],
			["swine", true, "isSingular: swine (special)"],

			["word", true, 'isSingular: word'],
			["words", false, "isSingular: words"],

			["cookie", true, "isSingular: cookie"],
			["cookies", false, "isSingular: cookies"],
			["database", true, "isSingular: database"],
			["databases", false, "isSingular: databases"],
			["crisis", true, "isSingular: crisis"],
			["crises", false, "isSingular: crises"],
			["shoe", true, "isSingular: shoe"],
			["shoes", false, "isSingular: shoes"],
			["backhoe", true, "isSingular: backhoe"],
			["backhoes", false, "isSingular: backhoes"],
			["movie", true, "isSingular: movie"],
			["movies", false, "isSingular: movies"],
			["vie", true, "isSingular: vie"],
			["vies", false, "isSingular: vies"],
			["narrative", true, "isSingular: narrative"],
			["narratives", false, "isSingular: narratives"],
			["hive", true, "isSingular: hive"],
			["hives", false, "isSingular: hives"],
			["analysis", true, "isSingular: analysis"],
			["analyses", false, "isSingular: analyses"],
			["basis", true, "isSingular: basis"],
			["bases", false, "isSingular: bases"],
			["diagnosis", true, "isSingular: diagnosis"],
			["diagnoses", false, "isSingular: diagnoses"],
			["parenthesis", true, "isSingular: parenthesis"],
			["parentheses", false, "isSingular: parentheses"],
			["prognosis", true, "isSingular: prognosis"],
			["prognoses", false, "isSingular: prognoses"],
			["synopsis", true, "isSingular: synopsis"],
			["synopses", false, "isSingular: synopses"],
			["thesis", true, "isSingular: thesis"],
			["theses", false, "isSingular: theses"],
			["news", true, "isSingular: news"],
		];
	}

	/**
	 * Returns test data for testIsPlural()
	 *
	 * @return array
	 */
	public function getTestIsPlural()
	{
		$temp = $this->getTestIsSingular();
		$ret  = [];

		foreach ($temp as $items)
		{
			$items[1] = !$items[1];
			$items[2] = str_replace('isSingular:', 'isPlural:', $items[2]);
			$ret[]    = $items;
		}

		return $ret;
	}

	/**
	 * Returns test data for singularize()
	 *
	 * @return array
	 */
	public function getTestSingularizeData()
	{
		return [
			["move", "move", "Singularise: Move"],
			["moves", "move", "Singularise: Moves"],
			["sex", "sex", "Singularise: Sex"],
			["sexes", "sex", "Singularise: Sexes"],
			["child", "child", "Singularise: Child"],
			["children", "child", "Singularise: Children"],
			["woman", "woman", "Pluralisation of words in -an not honoured"],
			["women", "woman", "Should return the same as it's already a plural (words in -an)"],
			["foot", "foot", "Singularise: Foot"],
			["feet", "foot", "Singularise: Feet"],
			["person", "person", "Singularise: Person"],
			["people", "person", "Singularise: People"],
			["taxon", "taxon", "Singularise: Taxon"],
			["taxa", "taxon", "Singularise: Taxa"],
			["quiz", "quiz", "Singularise: Quiz"],
			["quizzes", "quiz", "Singularise: Quizzes"],
			["ox", "ox", "Singularise: Ox"],
			["oxen", "ox", "Singularise: Oxen"],
			["mouse", "mouse", "Singularise: Mouse"],
			["mice", "mouse", "Singularise: Mice"],
			["matrix", "matrix", "Singularise: Matrix"],
			["matrices", "matrix", "Singularise: Matrices"],
			["vertex", "vertex", "Singularise: Vertex"],
			["vertices", "vertex", "Singularise: Vertices"],
			["index", "index", "Singularise: Index"],
			["indices", "index", "Singularise: Indices"],
			["suffix", "suffix", "Singularise: Suffix"],
			["suffices", "suffix", "Singularise: Suffices"],
			["codex", "codex", "Singularise: Codex"],
			["codices", "codex", "Singularise: Codices"],
			["onyx", "onyx", "Singularise: onyx"],
			["onyxes", "onyx", "Singularise: onyxes"],
			["leech", "leech", "Singularise: Leech"],
			["leeches", "leech", "Singularise: Leeches"],
			["glass", "glass", "Singularise: Glass"],
			["glasses", "glass", "Singularise: Glasses"],
			["mesh", "mesh", "Singularise: Mesh"],
			["meshes", "mesh", "Singularise: Meshes"],
			["soliloquy", "soliloquy", "Singularise: Soliloquy"],
			["soliloquies", "soliloquy", "Singularise: Soliloquies"],
			["baby", "baby", "Singularise: Baby"],
			["babies", "baby", "Singularise: Babies"],
			["elf", "elf", "Singularise: Elf"],
			["elves", "elf", "Singularise: Elves"],
			["life", "life", "Singularise: Life"],
			["lives", "life", "Singularise: Lives"],
			["antithesis", "antithesis", "Singularise: Antitheses"],
			["antitheses", "antithesis", "Singularise: Antitheses"],
			["consortium", "consortium", "Singularise: consortium"],
			["consortia", "consortium", "Singularise: consortia"],
			["addendum", "addendum", "Singularise: addendum"],
			["addenda", "addendum", "Singularise: addenda"],
			["alumna", "alumna", "Singularise: alumna"],
			["alumnae", "alumna", "Singularise: alumnae"],
			["formula", "formula", "Singularise: formula"],
			["formulae", "formula", "Singularise: formulae"],
			["buffalo", "buffalo", "Singularise: buffalo"],
			["buffaloes", "buffalo", "Singularise: buffaloes"],
			["tomato", "tomato", "Singularise: tomato"],
			["tomatoes", "tomato", "Singularise: tomatoes"],
			["hero", "hero", "Singularise: hero"],
			["heroes", "hero", "Singularise: heroes"],
			["bus", "bus", "Singularise: bus"],
			["buses", "bus", "Singularise: buses"],
			["alias", "alias", "Singularise: alias"],
			["aliases", "alias", "Singularise: aliases"],
			["octopus", "octopus", "Singularise: octopus"],
			["octopi", "octopus", "Singularise: octopi"],
			["virus", "virus", "Singularise: virus"],
			["viri", "virus", "Singularise: viri"],
			["genus", "genus", "Singularise: genus"],
			["genera", "genus", "Singularise: genera"],
			["axis", "axis", "Singularise: axis"],
			["axes", "axis", "Singularise: axes"],
			["testis", "testis", "Singularise: testis"],
			["testes", "testis", "Singularise: testes"],

			["dwarf", "dwarf", "Singularise: Dwarf"],
			["dwarves", "dwarf", "Singularise: Dwarves"],
			["guy", "guy", "Singularise: Guy"],
			["guy", "guy", "Singularise: Guy"],
			["relief", "relief", "Singularise: Relief"],
			["reliefs", "relief", "Singularise: Reliefs"],

			["aircraft", "aircraft", "Singularise: aircraft (special)"],
			["cannon", "cannon", "Singularise: cannon (special)"],
			["deer", "deer", "Singularise: deer (special)"],
			["equipment", "equipment", "Singularise: equipment (special)"],
			["fish", "fish", "Singularise: Fish (special)"],
			["information", "information", "Singularise: information (special)"],
			["money", "money", "Singularise: money (special)"],
			["moose", "moose", "Singularise: moose (special)"],
			["rice", "rice", "Singularise: rice (special)"],
			["series", "series", "Singularise: series (special)"],
			["sheep", "sheep", "Singularise: sheep (special)"],
			["species", "species", "Singularise: species (special)"],
			["swine", "swine", "Singularise: swine (special)"],

			["word", "word", 'Should return singular'],
			["words", "word", "Should return the same as it's already a singular"],

			["cookie", "cookie", "Singularise: cookie"],
			["cookies", "cookie", "Singularise: cookies"],
			["database", "database", "Singularise: database"],
			["databases", "database", "Singularise: databases"],
			["crisis", "crisis", "Singularise: crisis"],
			["crises", "crisis", "Singularise: crises"],
			["shoe", "shoe", "Singularise: shoe"],
			["shoes", "shoe", "Singularise: shoes"],
			["backhoe", "backhoe", "Singularise: backhoe"],
			["backhoes", "backhoe", "Singularise: backhoes"],
			["menu", "menu", "Singularise: menu"],
			["menus", "menu", "Singularise: menu"],
			["movie", "movie", "Singularise: movie"],
			["movies", "movie", "Singularise: movies"],
			["vie", "vie", "Singularise: vie"],
			["vies", "vie", "Singularise: vies"],
			["narrative", "narrative", "Singularise: narrative"],
			["narratives", "narrative", "Singularise: narratives"],
			["hive", "hive", "Singularise: hive"],
			["hives", "hive", "Singularise: hives"],
			["analysis", "analysis", "Singularise: analysis"],
			["analyses", "analysis", "Singularise: analyses"],
			["basis", "basis", "Singularise: basis"],
			["bases", "basis", "Singularise: bases"],
			["diagnosis", "diagnosis", "Singularise: diagnosis"],
			["diagnoses", "diagnosis", "Singularise: diagnoses"],
			["parenthesis", "parenthesis", "Singularise: parenthesis"],
			["parentheses", "parenthesis", "Singularise: parentheses"],
			["prognosis", "prognosis", "Singularise: prognosis"],
			["prognoses", "prognosis", "Singularise: prognoses"],
			["synopsis", "synopsis", "Singularise: synopsis"],
			["synopses", "synopsis", "Singularise: synopses"],
			["thesis", "thesis", "Singularise: thesis"],
			["theses", "thesis", "Singularise: theses"],
			["news", "news", "Singularise: news"],

		];
	}

	/**
	 * Returns test data for camelize()
	 *
	 * @return array
	 */
	public function getTestCamelizeData()
	{
		return [
			["foo_bar", "FooBar", 'Underscores must act as camelization points'],
			["foo bar", "FooBar", 'Spaces must act as camelization points'],
			["foo's bar", "FooSBar", 'Punctuation must be stripped out'],
			["foo.bar.123", "FooBar123", 'Numbers must be preserved'],
		];
	}

	/**
	 * Returns test data for underscore()
	 *
	 * @return array
	 */
	public function getTestUnderscoreData()
	{
		return [
			["foo bar", "foo_bar", 'Spaces must act as underscore points'],
			["FooBar", "foo_bar", 'CamelCase must be converted'],
		];
	}

	/**
	 * Returns test data for explode()
	 *
	 * @return array
	 */
	public function getTestExplodeData()
	{
		return [
			["foo bar", ['foo', 'bar'], 'Spaces must act as underscore points'],
			["FooBar", ['foo', 'bar'], 'CamelCase must be converted'],
		];
	}

	/**
	 * Returns test data for implode()
	 *
	 * @return array
	 */
	public function getTestImplodeData()
	{
		return [
			[['foo', 'bar'], "FooBar", 'Implosion failed'],
		];
	}

	/**
	 * Returns test data for humanize()
	 *
	 * @return array
	 */
	public function getTestHumanizeData()
	{
		return [
			["foo_bar", 'Foo Bar', 'Humanize failed'],
			["this_is_a_test", 'This Is A Test', 'Humanize failed'],
		];
	}

	/**
	 * Returns test data for variableize()
	 *
	 * @return array
	 */
	public function getTestVariableizeData()
	{
		return [
			'Underscores must act as camelization points' => [
				"foo_bar", "fooBar", 'Underscores must act as camelization points',
			],
			'Spaces must act as camelization points'      => [
				"foo bar", "fooBar", 'Spaces must act as camelization points',
			],
			'Punctuation must be stripped out'            => [
				"foo's bar", "fooSBar", 'Punctuation must be stripped out',
			],
			'Numbers must be preserved'                   => ["foo.bar.123", "fooBar123", 'Numbers must be preserved'],
		];
	}

	/**
	 * Test deleteCache method
	 *
	 * @covers FOF40\Inflector\Inflector::deleteCache
	 * @uses   FOF40\Tests\Helpers\ReflectionHelper::setValue
	 * @uses   FOF40\Tests\Helpers\ReflectionHelper::getValue
	 *
	 * @return  void
	 */
	public function testDeleteCache()
	{
		$container = new TestContainer();
		$myCache   = [
			'singularized' => ['foobar' => 'foobars'],
			'pluralized'   => ['foobars' => 'foobar'],
		];
		ReflectionHelper::setValue($container->inflector, 'cache', $myCache);

		$container->inflector->deleteCache();

		$newCache = ReflectionHelper::getValue($container->inflector, 'cache');

		$this->assertEmpty(
			$newCache['singularized'],
			'Line: ' . __LINE__ . '.'
		);

		$this->assertEmpty(
			$newCache['pluralized'],
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test addWord method
	 *
	 * @covers FOF40\Inflector\Inflector::addWord
	 * @uses   FOF40\Inflector\Inflector::singularize
	 * @uses   FOF40\Inflector\Inflector::pluralize
	 *
	 * @return  void
	 */
	public function testAddWord()
	{
		$container = new TestContainer();

		$container->inflector->addWord('xoxosingular', 'xoxoplural');

		$res = $container->inflector->singularize('xoxoplural');
		$this->assertEquals($res, 'xoxosingular', 'Custom word could not be singularized');

		$res = $container->inflector->pluralize('xoxosingular');
		$this->assertEquals($res, 'xoxoplural', 'Custom word could not be pluralized');
	}

	/**
	 * Test pluralize method
	 *
	 * @covers       FOF40\Inflector\Inflector::pluralize
	 * @uses         FOF40\Inflector\Inflector::deleteCache
	 *
	 * @dataProvider getTestPluralizeData
	 */
	public function testPluralize($word, $expect, $message)
	{
		$container = new TestContainer();
		$container->inflector->deleteCache();
		$res = $container->inflector->pluralize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test singularize method
	 *
	 * @covers       FOF40\Inflector\Inflector::singularize
	 * @uses         FOF40\Inflector\Inflector::deleteCache
	 *
	 * @dataProvider getTestSingularizeData
	 */
	public function testSingularize($word, $expect, $message)
	{
		$container = new TestContainer();
		$container->inflector->deleteCache();
		$res = $container->inflector->singularize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test camelize method
	 *
	 * @covers       FOF40\Inflector\Inflector::camelize
	 *
	 * @dataProvider getTestCamelizeData
	 */
	public function testCamelize($word, $expect, $message)
	{
		$container = new TestContainer();
		$res       = $container->inflector->camelize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test underscore method
	 *
	 * @covers       FOF40\Inflector\Inflector::underscore
	 *
	 * @dataProvider getTestUnderscoreData
	 */
	public function testUnderscore($word, $expect, $message)
	{
		$container = new TestContainer();
		$res       = $container->inflector->underscore($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test explode method
	 *
	 * @covers       FOF40\Inflector\Inflector::explode
	 *
	 * @dataProvider getTestExplodeData
	 */
	public function testExplode($word, $expect, $message)
	{
		$container = new TestContainer();
		$res       = $container->inflector->explode($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test implode method
	 *
	 * @covers       FOF40\Inflector\Inflector::implode
	 *
	 * @dataProvider getTestImplodeData
	 */
	public function testImplode($word, $expect, $message)
	{
		$container = new TestContainer();
		$res       = $container->inflector->implode($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test humanize method
	 *
	 * @covers       FOF40\Inflector\Inflector::humanize
	 *
	 * @dataProvider getTestHumanizeData
	 */
	public function testHumanize($word, $expect, $message)
	{
		$container = new TestContainer();
		$res       = $container->inflector->humanize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test variableize method
	 *
	 * @covers       FOF40\Inflector\Inflector::variablize
	 *
	 * @dataProvider getTestVariableizeData
	 */
	public function testVariableize($word, $expect, $message)
	{
		$container = new TestContainer();
		$res       = $container->inflector->variablize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test isSingular method
	 *
	 * @covers       FOF40\Inflector\Inflector::isSingular
	 * @uses         FOF40\Inflector\Inflector::deleteCache
	 *
	 * @dataProvider getTestIsSingular
	 */
	public function testIsSingular($word, $expect, $message)
	{
		$container = new TestContainer();
		$container->inflector->deleteCache();

		$res = $container->inflector->isSingular($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test isPlural method
	 *
	 * @covers       FOF40\Inflector\Inflector::isPlural
	 * @uses         FOF40\Inflector\Inflector::deleteCache
	 *
	 * @dataProvider getTestIsPlural
	 */
	public function testIsPlural($word, $expect, $message)
	{
		$container = new TestContainer();
		$container->inflector->deleteCache();

		$res = $container->inflector->isPlural($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}
}
