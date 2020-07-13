<?php

declare(strict_types=1);

/**
 * @copyright 2020, Maxence Lange <maxence@artificial-owl.com>
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCP\Webfinger\Model;


use JsonSerializable;
use OC\Webfinger\Exceptions\UnknownDataException;

/**
 * @since 20.0.0
 *
 * @package OCP\Webfinger\Model
 */
interface IWebfingerObject {


	/**
	 * @return array
	 * @since 20.0.0
	 */
	public function getData(): array;

	/**
	 * @param array $data
	 *
	 * @return IWebfingerObject
	 * @since 20.0.0
	 *
	 */
	public function setData(array $data): IWebfingerObject;


	/**
	 * @param string $k
	 * @param string $v
	 *
	 * @return $this
	 * @since 20.0.0
	 */
	public function setEntry(string $k, string $v): IWebfingerObject;

	/**
	 * @param string $k
	 * @param array $v
	 *
	 * @return $this
	 * @since 20.0.0
	 */
	public function setEntryArray(string $k, array $v): IWebfingerObject;

	/**
	 * @param string $k
	 * @param int $v
	 *
	 * @return $this
	 * @since 20.0.0
	 */
	public function setEntryInt(string $k, int $v): IWebfingerObject;

	/**
	 * @param string $k
	 * @param bool $v
	 *
	 * @return $this
	 * @since 20.0.0
	 */
	public function setEntryBool(string $k, bool $v): IWebfingerObject;

	/**
	 * @param string $k
	 * @param JsonSerializable $v
	 *
	 * @return $this
	 * @since 20.0.0
	 */
	public function setEntrySerializable(string $k, JsonSerializable $v): IWebfingerObject;


	/**
	 * @param string $k
	 * @param string $v
	 *
	 * @return IWebfingerObject
	 * @throws UnknownDataException
	 * @since 20.0.0
	 *
	 */
	public function addEntry(string $k, string $v): IWebfingerObject;

	/**
	 * @param string $k
	 * @param int $v
	 *
	 * @return IWebfingerObject
	 * @throws UnknownDataException
	 * @since 20.0.0
	 *
	 */
	public function addEntryInt(string $k, int $v): IWebfingerObject;

	/**
	 * @param string $k
	 * @param array $v
	 *
	 * @return IWebfingerObject
	 * @throws UnknownDataException
	 * @since 20.0.0
	 */
	public function addEntryArray(string $k, array $v): IWebfingerObject;


	/**
	 * @param string $k
	 *
	 * @return string
	 * @throws UnknownDataException
	 * @since 20.0.0
	 */
	public function getEntry(string $k): string;

	/**
	 * @param string $k
	 *
	 * @return array
	 * @throws UnknownDataException
	 * @since 20.0.0
	 */
	public function getEntryArray(string $k): array;

	/**
	 * @param string $k
	 *
	 * @return int
	 * @throws UnknownDataException
	 * @since 20.0.0
	 *
	 */
	public function getEntryInt(string $k): int;

	/**
	 * @param string $k
	 *
	 * @return bool
	 * @throws UnknownDataException
	 * @since 20.0.0
	 *
	 */
	public function getEntryBool(string $k): bool;

}
