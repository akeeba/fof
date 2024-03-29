<?php
/**
 * @package   FOF
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace FOF40\Download;

defined('_JEXEC') || die;

use FOF40\Download\Exception\DownloadError;

/**
 * Interface DownloadInterface
 *
 * @codeCoverageIgnore
 */
interface DownloadInterface
{
	/**
	 * Does this download adapter support downloading files in chunks?
	 *
	 * @return  boolean  True if chunk download is supported
	 */
	public function supportsChunkDownload(): bool;

	/**
	 * Does this download adapter support reading the size of a remote file?
	 *
	 * @return  boolean  True if remote file size determination is supported
	 */
	public function supportsFileSize(): bool;

	/**
	 * Is this download class supported in the current server environment?
	 *
	 * @return  boolean  True if this server environment supports this download class
	 */
	public function isSupported(): bool;

	/**
	 * Get the priority of this adapter. If multiple download adapters are
	 * supported on a site, the one with the highest priority will be
	 * used.
	 *
	 * @return  int
	 */
	public function getPriority(): int;

	/**
	 * Returns the name of this download adapter in use
	 *
	 * @return  string
	 */
	public function getName(): string;

	/**
	 * Download a part (or the whole) of a remote URL and return the downloaded
	 * data. You are supposed to check the size of the returned data. If it's
	 * smaller than what you expected you've reached end of file. If it's empty
	 * you have tried reading past EOF. If it's larger than what you expected
	 * the server doesn't support chunk downloads.
	 *
	 * If this class' supportsChunkDownload returns false you should assume
	 * that the $from and $to parameters will be ignored.
	 *
	 * @param string   $url    The remote file's URL
	 * @param int|null $from   Byte range to start downloading from. Use null for start of file.
	 * @param int|null $to     Byte range to stop downloading. Use null to download the entire file ($from is ignored)
	 * @param array    $params Additional params that will be added before performing the download
	 *
	 * @return  string  The raw file data retrieved from the remote URL.
	 *
	 * @throws  DownloadError  A generic exception is thrown on error
	 */
	public function downloadAndReturn(string $url, ?int $from = null, ?int $to = null, array $params = []): string;

	/**
	 * Get the size of a remote file in bytes
	 *
	 * @param string $url The remote file's URL
	 *
	 * @return  integer  The file size, or -1 if the remote server doesn't support this feature
	 */
	public function getFileSize(string $url): int;
}
