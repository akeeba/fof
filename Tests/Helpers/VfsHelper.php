<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2021 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 2, or later
 */

namespace FOF30\Tests\Helpers;

class VfsHelper
{
    /**
     * Converts an array of paths to a tree that could be used to feed vfsStream::setup.
     * File contents should be passed in the second arguments as indexed array filename => contents.
     * Example:
     *
     * $paths = array(
     *   'administrator/components/com_foftest/views/bare',
     *   'administrator/components/com_foftest/views/bares',
     *   'administrator/components/com_foftest/views/foobar',
     *   'administrator/components/com_foftest/views/foobars/metadata.xml'
     * );
     *
     * $contents = array(
     *   'metadata.xml' => 'your content goes here'
     * );
     *
     * @param  array   $paths
     * @param  array   $contents
     *
     * @return array
     */
    public static function createArrayDir($paths, $contents = array())
    {
        $tree = array();

        foreach ($paths as $path)
        {
            $pathParts = explode('/', $path);
            $subTree   = array(array_pop($pathParts));

            if(strpos($subTree[0], '.') !== false)
            {
                $content = '';

                if(isset($contents[$subTree[0]]))
                {
                    $content = $contents[$subTree[0]];
                }

                $subTree = array($subTree[0] => $content);
            }
            else
            {
                $subTree = array($subTree[0] => array());
            }

            foreach (array_reverse($pathParts) as $dir)
            {
                $subTree = array($dir => $subTree);
            }

            $tree = array_merge_recursive($tree, $subTree);
        }

        return $tree;
    }
}
