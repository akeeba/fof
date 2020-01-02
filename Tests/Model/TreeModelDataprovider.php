<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


class TreeModelDataprovider
{
	public static function getTestForceDelete()
	{
		/*
		 * At the moment I can only test when onBeforeDelete return false in the first level only.
		 * That's because the iterator is spawning a new class every time, so the mock we setup is not used
		 * and the check if performed vs the "real" object, which of course returns false.
		 */

		// Delete a single leaf item
		$data[] = [
			[
				'loadid' => null,
				'delete' => 15,
				'mock'   => [
					'before' => function () {
						return true;
					},
				],
			],
			[
				'case'    => 'Delete a single leaf item',
				'return'  => true,
				'deleted' => [15],
				// Associative array where the index is the node id, so I can double check if the lft rgt values
				// are correctly updated
				'nodes'   => [
					1  => ['lft' => 1, 'rgt' => 30],
					9  => ['lft' => 16, 'rgt' => 29],
					14 => ['lft' => 25, 'rgt' => 28],
				],
			],
		];

		// Delete a single leaf item (loaded table)
		$data[] = [
			[
				'loadid' => 15,
				'delete' => null,
				'mock'   => [
					'before' => function () {
						return true;
					},
				],
			],
			[
				'case'    => 'Delete a single leaf item (loaded table)',
				'return'  => true,
				'deleted' => [15],
				// Associative array where the index is the node id, so I can double check if the lft rgt values
				// are correctly updated
				'nodes'   => [
					1  => ['lft' => 1, 'rgt' => 30],
					9  => ['lft' => 16, 'rgt' => 29],
					14 => ['lft' => 25, 'rgt' => 28],
				],
			],
		];

		// Delete a single leaf item - prevented
		$data[] = [
			[
				'loadid' => null,
				'delete' => 15,
				'mock'   => [
					'before' => function ($self) {
						$k = $self->getIdFieldName();
						if ($self->$k == 15)
						{
							throw new \Exception();
						}

						return true;
					},
				],
			],
			[
				'case'    => 'Delete a single leaf item - prevented',
				'return'  => false,
				'deleted' => [],
				// Associative array where the index is the node id, so I can double check if the lft rgt values
				// are correctly updated
				'nodes'   => [
					1  => ['lft' => 1, 'rgt' => 32],
					9  => ['lft' => 16, 'rgt' => 31],
					14 => ['lft' => 25, 'rgt' => 30],
				],
			],
		];

		// Delete a single trunk item
		$data[] = [
			[
				'loadid' => null,
				'delete' => 14,
				'mock'   => [
					'before' => function () {
						return true;
					},
				],
			],
			[
				'case'    => 'Delete a single trunk item',
				'return'  => true,
				'deleted' => [14, 15, 16],
				// Associative array where the index is the node id, so I can double check if the lft rgt values
				// are correctly updated
				'nodes'   => [
					1 => ['lft' => 1, 'rgt' => 26],
					9 => ['lft' => 16, 'rgt' => 25],
				],
			],
		];

		// Delete a single trunk item (loaded table)
		$data[] = [
			[
				'loadid' => 14,
				'delete' => null,
				'mock'   => [
					'before' => function () {
						return true;
					},
				],
			],
			[
				'case'    => 'Delete a single trunk item (loaded table)',
				'return'  => true,
				'deleted' => [14, 15, 16],
				// Associative array where the index is the node id, so I can double check if the lft rgt values
				// are correctly updated
				'nodes'   => [
					1 => ['lft' => 1, 'rgt' => 26],
					9 => ['lft' => 16, 'rgt' => 25],
				],
			],
		];

		// Delete a single trunk item - prevented
		$data[] = [
			[
				'loadid' => null,
				'delete' => 14,
				'mock'   => [
					'before' => function ($self) {
						$k = $self->getIdFieldName();
						if ($self->$k == 14)
						{
							throw new \Exception();
						}

						return true;
					},
				],
			],
			[
				'case'    => 'Delete a single trunk item - prevented',
				'return'  => false,
				'deleted' => [],
				// Associative array where the index is the node id, so I can double check if the lft rgt values
				// are correctly updated
				'nodes'   => [
					1 => ['lft' => 1, 'rgt' => 32],
					9 => ['lft' => 16, 'rgt' => 31],
				],
			],
		];

		return $data;
	}

	public static function getTestCheck()
	{
		$data[] = [
			[
				'table'  => '#__foftest_nestedsets',
				'id'     => 'foftest_nestedset_id',
				'fields' => [
					'title' => 'Test title',
					'slug'  => '',
				],
			],
			[
				'case'   => 'Title is set and slug is empty',
				'fields' => [
					'slug' => 'test-title',
					'hash' => sha1('test-title'),
				],
			],
		];

		$data[] = [
			[
				'table'  => '#__foftest_nestedsets',
				'id'     => 'foftest_nestedset_id',
				'fields' => [
					'title' => 'Test title',
					'slug'  => 'old-slug',
				],
			],
			[
				'case'   => 'Title and slug are set',
				'fields' => [
					'slug' => 'old-slug',
					'hash' => sha1('old-slug'),
				],
			],
		];

		$data[] = [
			[
				'table'  => '#__foftest_nestedbares',
				'id'     => 'id',
				'fields' => [],
			],
			[
				'case'   => 'Bare table without hash nor slug fields',
				'fields' => [
					'slug' => null,
					'hash' => null,
				],
			],
		];

		return $data;
	}

	public static function getTestCreate()
	{
		// Create a node under the root
		$data[] = [
			[
				'root'   => true,
				'loadid' => 1,
				'data'   => [
					'title' => 'Created node',
				],
			],
		];

		// Create a node in any other position
		$data[] = [
			[
				'root'   => false,
				'loadid' => 2,
				'data'   => [
					'title' => 'Created node',
				],
			],
		];

		return $data;
	}

	public static function getTestInsertAsFirstChildOf()
	{
		// Creating a new node
		$data[] = [
			[
				'loadid'   => 0,
				'parentid' => 14,
				'title'    => 'First child',
			],
			[
				'case' => 'Creating a new node',
			],
		];

		// Copying an existing node of the same parent (it's not the first child)
		$data[] = [
			[
				'loadid'   => 16,
				'parentid' => 14,
				'title'    => '',
			],
			[
				'case' => "Copying an existing node of the same parent (it's not the first child)",
			],
		];

		// Copying an existing node of the same parent (it's the first child)
		$data[] = [
			[
				'loadid'   => 15,
				'parentid' => 14,
				'title'    => '',
			],
			[
				'case' => "Copying an existing node of the same parent (it's the first child)",
			],
		];

		// Copying an existing node of another parent
		$data[] = [
			[
				'loadid'   => 4,
				'parentid' => 14,
				'title'    => '',
			],
			[
				'case' => 'Copying an existing node of another parent',
			],
		];

		return $data;
	}

	public static function getTestInsertAsLastChildOf()
	{
		// Creating a new node
		$data[] = [
			[
				'loadid'   => 0,
				'parentid' => 14,
				'title'    => 'Last child',
			],
			[
				'case' => 'Creating a new node',
			],
		];

		// Copying an existing node of the same parent (it's not the last child)
		$data[] = [
			[
				'loadid'   => 15,
				'parentid' => 14,
				'title'    => '',
			],
			[
				'case' => "Copying an existing node of the same parent (it's not the last child)",
			],
		];

		// Copying an existing node of the same parent (it's the last child)
		$data[] = [
			[
				'loadid'   => 16,
				'parentid' => 14,
				'title'    => '',
			],
			[
				'case' => "Copying an existing node of the same parent (it's the last child)",
			],
		];

		// Copying an existing node with children
		$data[] = [
			[
				'loadid'   => 10,
				'parentid' => 9,
				'title'    => '',
			],
			[
				'case' => 'Copying an existing node with children',
			],
		];

		// Copying an existing node of another parent
		$data[] = [
			[
				'loadid'   => 4,
				'parentid' => 14,
				'title'    => '',
			],
			[
				'case' => 'Copying an existing node of another parent',
			],
		];

		return $data;
	}

	public static function getTestInsertLeftOf()
	{
		// Creating a new node
		$data[] = [
			[
				'loadid'    => 0,
				'siblingid' => 13,
				'title'     => 'Left sibling',
			],
			[
				'case' => 'Creating a new node',
			],
		];

		// Copying an existing node
		$data[] = [
			[
				'loadid'    => 10,
				'siblingid' => 13,
				'title'     => '',
			],
			[
				'case' => 'Copying an existing node',
			],
		];

		return $data;
	}

	public static function getTestInsertRightOf()
	{
		// Creating a new node
		$data[] = [
			[
				'loadid'    => 0,
				'siblingid' => 13,
				'title'     => 'Right sibling',
			],
			[
				'case' => 'Creating a new node',
			],
		];

		// Copying an existing node
		$data[] = [
			[
				'loadid'    => 10,
				'siblingid' => 13,
				'title'     => '',
			],
			[
				'case' => 'Copying an existing node',
			],
		];

		return $data;
	}

	public static function getTestMoveLeft()
	{
		// Node in the middle of another two
		$data[] = [
			[
				'loadid' => 13,
			],
			[
				'case'    => 'Node in the middle of another two',
				'counter' => 1,
				'sibling' => 10,
			],
		];

		// Root node
		$data[] = [
			[
				'loadid' => 1,
			],
			[
				'case'    => 'Root node',
				'counter' => 0,
				'sibling' => null,
			],
		];

		// Already a leftmost node
		$data[] = [
			[
				'loadid' => 10,
			],
			[
				'case'    => 'Already a leftmost node',
				'counter' => 0,
				'sibling' => null,
			],
		];

		return $data;
	}

	public static function getTestMoveRight()
	{
		// Node in the middle of another two
		$data[] = [
			[
				'loadid' => 13,
			],
			[
				'case'    => 'Node in the middle of another two',
				'counter' => 1,
				'sibling' => 14,
			],
		];

		// Root node
		$data[] = [
			[
				'loadid' => 1,
			],
			[
				'case'    => 'Root node',
				'counter' => 0,
				'sibling' => null,
			],
		];

		// Already a rightmost node
		$data[] = [
			[
				'loadid' => 14,
			],
			[
				'case'    => 'Already a rightmost node',
				'counter' => 0,
				'sibling' => null,
			],
		];

		return $data;
	}

	public static function getTestMoveToLeftOf()
	{
		// Moving a node to the left
		$data[] = [
			[
				'newRoot'   => false,
				'loadid'    => 13,
				'siblingid' => 10,
			],
			[
				'case'    => 'Moving a node to the left',
				'table'   => ['lft' => 17, 'rgt' => 18],
				'sibling' => ['lft' => 19, 'rgt' => 24],
			],
		];

		// Trying to move the leftmost node to the left (no changes at all)
		$data[] = [
			[
				'newRoot'   => false,
				'loadid'    => 10,
				'siblingid' => 13,
			],
			[
				'case'    => 'Trying to move the leftmost node to the left (no changes at all)',
				'table'   => ['lft' => 17, 'rgt' => 22],
				'sibling' => ['lft' => 23, 'rgt' => 24],
			],
		];

		// There are more roots, let's try to move one
		$data[] = [
			[
				'newRoot'   => true,
				'loadid'    => ['lft' => 33],
				'siblingid' => 1,
			],
			[
				'case'    => "There are more roots, let's try to move one",
				'table'   => ['lft' => 1, 'rgt' => 6],
				'sibling' => ['lft' => 7, 'rgt' => 38],
			],
		];

		return $data;
	}

	public static function getTestMoveToLeftOfException()
	{
		$data[] = [
			[
				'loadid'    => 0,
				'siblingid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'    => 1,
				'siblingid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtSibling',
			],
		];

		$data[] = [
			[
				'loadid'    => 0,
				'siblingid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestMoveToRightOf()
	{
		// Moving a node to the left
		$data[] = [
			[
				'newRoot'   => false,
				'loadid'    => 10,
				'siblingid' => 13,
			],
			[
				'case'    => 'Moving a node to the left',
				'table'   => ['lft' => 19, 'rgt' => 24],
				'sibling' => ['lft' => 17, 'rgt' => 18],
			],
		];

		// Trying to move the rightmost node to the right (no changes at all)
		$data[] = [
			[
				'newRoot'   => false,
				'loadid'    => 14,
				'siblingid' => 13,
			],
			[
				'case'    => 'Trying to move the rightmost node to the right (no changes at all)',
				'table'   => ['lft' => 25, 'rgt' => 30],
				'sibling' => ['lft' => 23, 'rgt' => 24],
			],
		];

		// There are more roots, let's try to move one
		$data[] = [
			[
				'newRoot'   => true,
				'loadid'    => 1,
				'siblingid' => ['lft' => 33],
			],
			[
				'case'    => "There are more roots, let's try to move one",
				'table'   => ['lft' => 7, 'rgt' => 38],
				'sibling' => ['lft' => 1, 'rgt' => 6],
			],
		];

		return $data;
	}

	public static function getTestMoveToRightOfException()
	{
		$data[] = [
			[
				'loadid'    => 0,
				'siblingid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'    => 1,
				'siblingid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtSibling',
			],
		];

		$data[] = [
			[
				'loadid'    => 0,
				'siblingid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestMakeFirstChildOf()
	{
		// Moving a single node
		$data[] = [
			[
				'loadid'   => 13,
				'parentid' => 2,
			],
			[
				'case'   => 'Moving a single node',
				'table'  => ['lft' => 3, 'rgt' => 4],
				'parent' => ['lft' => 2, 'rgt' => 17],
			],
		];

		// Moving an entire subtree
		$data[] = [
			[
				'loadid'   => 10,
				'parentid' => 2,
			],
			[
				'case'   => 'Moving an entire subtree',
				'table'  => ['lft' => 3, 'rgt' => 8],
				'parent' => ['lft' => 2, 'rgt' => 21],
			],
		];

		// Moving a single node under the same parent
		$data[] = [
			[
				'loadid'   => 13,
				'parentid' => 9,
			],
			[
				'case'   => 'Moving a single node under the same parent',
				'table'  => ['lft' => 17, 'rgt' => 18],
				'parent' => ['lft' => 16, 'rgt' => 31],
			],
		];

		return $data;
	}

	public static function getTestMakeFirstChildOfException()
	{
		$data[] = [
			[
				'loadid'   => 0,
				'parentid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'   => 1,
				'parentid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtParent',
			],
		];

		$data[] = [
			[
				'loadid'   => 0,
				'parentid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestMakeLastChildOf()
	{
		// Moving a single node
		$data[] = [
			[
				'loadid'   => 13,
				'parentid' => 2,
			],
			[
				'case'   => 'Moving a single node',
				'table'  => ['lft' => 15, 'rgt' => 16],
				'parent' => ['lft' => 2, 'rgt' => 17],
			],
		];

		// Moving an entire subtree
		$data[] = [
			[
				'loadid'   => 10,
				'parentid' => 2,
			],
			[
				'case'   => 'Moving an entire subtree',
				'table'  => ['lft' => 15, 'rgt' => 20],
				'parent' => ['lft' => 2, 'rgt' => 21],
			],
		];

		// Moving a single node under the same parent
		$data[] = [
			[
				'loadid'   => 13,
				'parentid' => 9,
			],
			[
				'case'   => 'Moving a single node under the same parent',
				'table'  => ['lft' => 29, 'rgt' => 30],
				'parent' => ['lft' => 16, 'rgt' => 31],
			],
		];

		return $data;
	}

	public static function getTestMakeLastChildOfException()
	{
		$data[] = [
			[
				'loadid'   => 0,
				'parentid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'   => 1,
				'parentid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtParent',
			],
		];

		$data[] = [
			[
				'loadid'   => 0,
				'parentid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestMakeRoot()
	{
		// Node is root
		$data[] = [
			[
				'mock' => [
					'isRoot' => true,
					'equals' => true,
				],
			],
			[
				'case' => 'Node is root',
				'move' => 0,
			],
		];

		// Node is equal to the root
		$data[] = [
			[
				'mock' => [
					'isRoot' => false,
					'equals' => true,
				],
			],
			[
				'case' => 'Node is equal to the root',
				'move' => 0,
			],
		];

		// Ok, we can move it
		$data[] = [
			[
				'mock' => [
					'isRoot' => false,
					'equals' => false,
				],
			],
			[
				'case' => 'Ok, we can move it',
				'move' => 1,
			],
		];

		return $data;
	}

	public static function getTestGetLevel()
	{
		// Node
		$data[] = [
			[
				'cache'  => null,
				'loadid' => 2,
			],
			[
				'case'  => 'Node',
				'level' => 1,
			],
		];

		// Root
		$data[] = [
			[
				'cache'  => null,
				'loadid' => 1,
			],
			[
				'case'  => 'Root',
				'level' => 0,
			],
		];

		// Cached value
		$data[] = [
			[
				'cache'  => 'cached',
				'loadid' => 1,
			],
			[
				'case'  => 'Cached value',
				'level' => 'cached',
			],
		];

		return $data;
	}

	public static function getTestGetParent()
	{
		// Root node, we simply return ourselves
		$data[] = [
			[
				'loadid' => 1,
				'cache'  => null,
			],
			[
				'case'   => 'Root node',
				'parent' => 1,
			],
		];

		// Child node, first level
		$data[] = [
			[
				'loadid' => 1,
				'cache'  => null,
			],
			[
				'case'   => 'Child node, first level',
				'parent' => 1,
			],
		];

		// Child node, first level
		$data[] = [
			[
				'loadid' => 9,
				'cache'  => null,
			],
			[
				'case'   => 'Child node, first level',
				'parent' => 1,
			],
		];

		// Child node
		$data[] = [
			[
				'loadid' => 16,
				'cache'  => null,
			],
			[
				'case'   => 'Child node',
				'parent' => 14,
			],
		];

		// Child node - wrong cache
		$data[] = [
			[
				'loadid' => 16,
				'cache'  => 'dummy',
			],
			[
				'case'   => 'Child node - wrong cache',
				'parent' => 14,
			],
		];

		// Child node - wrong cache 2
		$data[] = [
			[
				'loadid' => 16,
				'cache'  => new stdClass(),
			],
			[
				'case'   => 'Child node - wrong cache 2',
				'parent' => 14,
			],
		];

		// Child node - correct cache
		$data[] = [
			[
				'loadid' => 16,
				'cache'  => 'loadself',
			],
			[
				'case'   => 'Child node - correct cache',
				'parent' => 16,
			],
		];

		return $data;
	}

	public static function getTestIsRoot()
	{
		// Root node
		$data[] = [
			[
				'loadid' => 1,
				'mock'   => [
					'getLevel' => 0,
				],

			],
			[
				'case'     => '',
				'getLevel' => 0,
				'result'   => true,
			],
		];

		// Child node
		$data[] = [
			[
				'loadid' => 2,
				'mock'   => [
					'getLevel' => 1,
				],

			],
			[
				'case'     => '',
				'getLevel' => 1,
				'result'   => false,
			],
		];

		// Second root
		$data[] = [
			[
				'loadid' => 2,
				'mock'   => [
					'getLevel' => 0,
				],

			],
			[
				'case'     => '',
				'getLevel' => 1,
				'result'   => true,
			],
		];

		return $data;
	}

	public static function getTestIsLeaf()
	{
		$data[] = [
			[
				'lft' => 10,
				'rgt' => 11,
			],
			[
				'case'   => 'Node is a leaf',
				'result' => true,
			],
		];

		$data[] = [
			[
				'lft' => 10,
				'rgt' => 13,
			],
			[
				'case'   => 'Node is not a leaf',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestIsDescendantOf()
	{
		$data[] = [
			[
				'loadid'  => 10,
				'otherid' => 9,
			],
			[
				'case'   => 'Node is actually a descendant',
				'result' => true,
			],
		];

		$data[] = [
			[
				'loadid'  => 3,
				'otherid' => 9,
			],
			[
				'case'   => 'Node is not a descendant',
				'result' => false,
			],
		];

		$data[] = [
			[
				'loadid'  => 9,
				'otherid' => 9,
			],
			[
				'case'   => 'Node is itself',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestIsDescendantOfException()
	{
		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'  => 1,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtOther',
			],
		];

		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestIsSelfOrDescendantOf()
	{
		$data[] = [
			[
				'loadid'  => 10,
				'otherid' => 9,
			],
			[
				'case'   => 'Node is a descendant',
				'result' => true,
			],
		];

		$data[] = [
			[
				'loadid'  => 3,
				'otherid' => 9,
			],
			[
				'case'   => 'Node is not a descendant',
				'result' => false,
			],
		];

		$data[] = [
			[
				'loadid'  => 9,
				'otherid' => 9,
			],
			[
				'case'   => 'Node is itself',
				'result' => true,
			],
		];

		return $data;
	}

	public static function getTestIsSelfOrDescendantOfException()
	{
		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'  => 1,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtOther',
			],
		];

		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestEquals()
	{
		// The node is truly the same
		$data[] = [
			[
				'loadid'       => 4,
				'otherid'      => 4,
				'forceTableId' => null,
				'forceOtherId' => null,
			],
			[
				'case'   => 'The node is truly the same',
				'result' => true,
			],
		];

		// Id is different
		$data[] = [
			[
				'loadid'       => 4,
				'otherid'      => 4,
				'forceTableId' => 3,
				'forceOtherId' => null,
			],
			[
				'case'   => 'Id is different',
				'result' => false,
			],
		];

		// Lft/rgt value are different
		$data[] = [
			[
				'loadid'       => 4,
				'otherid'      => 12,
				'forceTableId' => 12,
				'forceOtherId' => null,
			],
			[
				'case'   => 'Lft/rgt value are different',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestEqualsException()
	{
		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'  => 1,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtOther',
			],
		];

		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestInSameScope()
	{
		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => true,
						'isRoot'  => false,
						'isChild' => false,
					],
					'other' => [
						'isLeaf'  => true,
						'isRoot'  => false,
						'isChild' => false,
					],
				],
			],
			[
				'case'   => 'Table and other node are leaf',
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => true,
						'isRoot'  => false,
						'isChild' => false,
					],
					'other' => [
						'isLeaf'  => false,
						'isRoot'  => true,
						'isChild' => false,
					],
				],
			],
			[
				'case'   => 'Table is leaf and other node is a root',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => true,
						'isRoot'  => false,
						'isChild' => false,
					],
					'other' => [
						'isLeaf'  => false,
						'isRoot'  => false,
						'isChild' => true,
					],
				],
			],
			[
				'case'   => 'Table is leaf and other node is a child',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => false,
						'isRoot'  => true,
						'isChild' => false,
					],
					'other' => [
						'isLeaf'  => false,
						'isRoot'  => true,
						'isChild' => false,
					],
				],
			],
			[
				'case'   => 'Table and other node are root',
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => false,
						'isRoot'  => true,
						'isChild' => false,
					],
					'other' => [
						'isLeaf'  => true,
						'isRoot'  => false,
						'isChild' => false,
					],
				],
			],
			[
				'case'   => 'Table is root and other node is leaf',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => false,
						'isRoot'  => true,
						'isChild' => false,
					],
					'other' => [
						'isLeaf'  => false,
						'isRoot'  => false,
						'isChild' => true,
					],
				],
			],
			[
				'case'   => 'Table is root and other node is child',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => false,
						'isRoot'  => false,
						'isChild' => true,
					],
					'other' => [
						'isLeaf'  => false,
						'isRoot'  => false,
						'isChild' => true,
					],
				],
			],
			[
				'case'   => 'Table and other node are children',
				'result' => true,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => false,
						'isRoot'  => false,
						'isChild' => true,
					],
					'other' => [
						'isLeaf'  => true,
						'isRoot'  => false,
						'isChild' => false,
					],
				],
			],
			[
				'case'   => 'Table is child and other node is leaf',
				'result' => false,
			],
		];

		$data[] = [
			[
				'mock' => [
					'table' => [
						'isLeaf'  => false,
						'isRoot'  => false,
						'isChild' => true,
					],
					'other' => [
						'isLeaf'  => false,
						'isRoot'  => true,
						'isChild' => false,
					],
				],
			],
			[
				'case'   => 'Table is child and other node is root',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestScopeImmediateDescendants()
	{
		$data[] = [
			[
				'loadid' => 5,
			],
			[
				'case'   => 'Node with direct descendants',
				'result' => [7, 9],
			],
		];

		$data[] = [
			[
				'loadid' => 8,
			],
			[
				'case'   => 'Leaf node',
				'result' => [0],
			],
		];

		$data[] = [
			[
				'loadid' => 2,
			],
			[
				'case'   => 'Node with several descendants',
				'result' => [3, 5],
			],
		];

		return $data;
	}

	public static function getTestRoot()
	{
		// The node is a root himself
		$data[] = [
			[
				'loadid'  => 1,
				'cache'   => null,
				'newRoot' => false,
			],
			[
				'case'   => 'The node is a root himself',
				'result' => 1,
			],
		];

		// Two roots - The node is a root himself
		$data[] = [
			[
				'loadid'  => ['lft' => 33],
				'cache'   => null,
				'newRoot' => true,
			],
			[
				'case'   => 'Two roots - The node is a root himself',
				'result' => 33,
			],
		];

		// Single root - The root is the immediate parent of the node
		$data[] = [
			[
				'loadid'  => 2,
				'cache'   => null,
				'newRoot' => false,
			],
			[
				'case'   => 'Single root - The root is the immediate parent of the node',
				'result' => 1,
			],
		];

		// Single root -  Node is deeper than first level
		$data[] = [
			[
				'loadid'  => 7,
				'cache'   => null,
				'newRoot' => false,
			],
			[
				'case'   => 'Single root -  Node is deeper than first level',
				'result' => 1,
			],
		];

		// Two roots - Node is deeper than first level
		$data[] = [
			[
				'loadid'  => 7,
				'cache'   => null,
				'newRoot' => true,
			],
			[
				'case'   => 'Two roots - Node is deeper than first level',
				'result' => 1,
			],
		];

		// The treeRoot is cached - wrong way
		$data[] = [
			[
				'loadid'  => 2,
				'cache'   => 1,
				'newRoot' => false,
			],
			[
				'case'   => 'The treeRoot is cached - wrong way',
				'result' => 1,
			],
		];

		// The treeRoot is cached - wrong way 2
		$data[] = [
			[
				'loadid'  => 2,
				'cache'   => new stdClass(),
				'newRoot' => false,
			],
			[
				'case'   => 'The treeRoot is cached - wrong way 2',
				'result' => 1,
			],
		];

		// The treeRoot is cached - right way
		$data[] = [
			[
				'loadid'  => 2,
				'cache'   => 'loadself',
				'newRoot' => false,
			],
			[
				'case'   => 'The treeRoot is cached - right way',
				'result' => 2,
			],
		];

		// Two roots - Your root is in another castle
		$data[] = [
			[
				'loadid'  => ['lft' => 37],
				'cache'   => null,
				'newRoot' => true,
			],
			[
				'case'   => 'Two roots - Your root is in another castle',
				'result' => 33,
			],
		];

		// Two roots - Your root is in another castle
		$data[] = [
			[
				'loadid'  => ['lft' => 34],
				'cache'   => null,
				'newRoot' => true,
			],
			[
				'case'   => 'Two roots - Your root is in another castle',
				'result' => 33,
			],
		];

		return $data;
	}

	public static function getTestRootException()
	{
		// Can't find any valid root
		$data[] = [
			[
				'loadid'    => 2,
				'mock'      => [
					'firstOrFail' => [true, false],
				],
				'wrongNode' => false,
			],
			[
				'exception' => 'TreeRootNotFound',
			],
		];

		// There 2 roots, I get an error while trying to load all the roots
		$data[] = [
			[
				'loadid'    => 2,
				'mock'      => [
					'firstOrFail' => [false, true],
				],
				'wrongNode' => true,
			],
			[
				'exception' => 'TreeRootNotFound',
			],
		];

		// There 2 roots, I get an error while getting the correct one
		$data[] = [
			[
				'loadid'    => 2,
				'mock'      => [
					'firstOrFail' => [false, true],
				],
				'wrongNode' => false,
			],
			[
				'exception' => 'TreeRootNotFound',
			],
		];

		return $data;
	}

	public static function getTestGetNestedList()
	{
		$data[] = [
			[
				'column'    => 'title',
				'key'       => 'foftest_nestedset_id',
				'separator' => ' ',
			],
			[
				'result' => [
					1  => 'ROOT',
					2  => ' Electronics',
					3  => '  Audio',
					4  => '  Imaging',
					5  => '   Photography',
					6  => '    Compact',
					7  => '    DSLR',
					8  => '   Video',
					9  => ' Computers',
					10 => '  Smartphones',
					11 => '   Apple',
					12 => '   Android',
					13 => '  Laptops',
					14 => '  Tablets',
					15 => '   Apple',
					16 => '   Android',
				],
			],
		];

		$data[] = [
			[
				'column'    => 'title',
				'key'       => 'foftest_nestedset_id',
				'separator' => '-',
			],
			[
				'result' => [
					1  => 'ROOT',
					2  => '-Electronics',
					3  => '--Audio',
					4  => '--Imaging',
					5  => '---Photography',
					6  => '----Compact',
					7  => '----DSLR',
					8  => '---Video',
					9  => '-Computers',
					10 => '--Smartphones',
					11 => '---Apple',
					12 => '---Android',
					13 => '--Laptops',
					14 => '--Tablets',
					15 => '---Apple',
					16 => '---Android',
				],
			],
		];

		$data[] = [
			[
				'column'    => '',
				'key'       => '',
				'separator' => '-',
			],
			[
				'result' => [
					1  => 'ROOT',
					2  => '-Electronics',
					3  => '--Audio',
					4  => '--Imaging',
					5  => '---Photography',
					6  => '----Compact',
					7  => '----DSLR',
					8  => '---Video',
					9  => '-Computers',
					10 => '--Smartphones',
					11 => '---Apple',
					12 => '---Android',
					13 => '--Laptops',
					14 => '--Tablets',
					15 => '---Apple',
					16 => '---Android',
				],
			],
		];

		$data[] = [
			[
				'column'    => 'slug',
				'key'       => 'foftest_nestedset_id',
				'separator' => ' ',
			],
			[
				'result' => [
					1  => 'root',
					2  => ' electronics',
					3  => '  audio',
					4  => '  imaging',
					5  => '   photography',
					6  => '    compact',
					7  => '    dslr',
					8  => '   video',
					9  => ' computers',
					10 => '  smartphones',
					11 => '   apple',
					12 => '   android',
					13 => '  laptops',
					14 => '  tablets',
					15 => '   apple-1',
					16 => '   android',
				],
			],
		];

		return $data;
	}

	public static function getTestInsideSubTree()
	{
		$data[] = [
			[
				'loadid'  => 4,
				'otherid' => 2,
			],
			[
				'case'   => 'Node is inside the other one',
				'result' => true,
			],
		];

		$data[] = [
			[
				'loadid'  => 16,
				'otherid' => 2,
			],
			[
				'case'   => 'Node is inside another subtree',
				'result' => false,
			],
		];

		return $data;
	}

	public static function getTestInsideSubTreeException()
	{
		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		$data[] = [
			[
				'loadid'  => 1,
				'otherid' => 0,
			],
			[
				'exception' => 'TreeInvalidLftRgtOther',
			],
		];

		$data[] = [
			[
				'loadid'  => 0,
				'otherid' => 1,
			],
			[
				'exception' => 'TreeInvalidLftRgtCurrent',
			],
		];

		return $data;
	}

	public static function getTestFindByPath()
	{
		$data[] = [
			[
				'path' => '',
			],
			[
				'case'   => 'Path is empty',
				'result' => false,
				'id'     => '',
			],
		];

		$data[] = [
			[
				'path' => '/',
			],
			[
				'case'   => 'Path is just a slash',
				'result' => true,
				'id'     => 1,
			],
		];

		/*$data[] = array(
			array(
				'path' => '/root/electronics/audio'
			),
			array(
				'case' => 'Full path, not leading with a slash',
				'result' => true,
				'id'   => 3
			)
		);*/

		return $data;
	}
}
