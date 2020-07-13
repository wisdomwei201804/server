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


/**
 * @since 20.0.0
 *
 * @package OCP\Webfinger\Model
 */
interface IWebfingerLink {


	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getHref(): string;

	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getType(): string;

	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getRel(): string;

	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getTemplate(): string;


	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setHref(string $value): IWebfingerLink;

	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setType(string $value): IWebfingerLink;

	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setRel(string $value): IWebfingerLink;

	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setTemplate(string $value): IWebfingerLink;

}
