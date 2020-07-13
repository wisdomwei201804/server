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

namespace OC\Webfinger\Model;

use JsonSerializable;
use OCP\Webfinger\Model\IWebfingerLink;


/**
 * @since 20.0.0
 *
 * @package OC\Webfinger\Model
 */
final class WebfingerLink extends WebfingerObject implements IWebfingerLink, JsonSerializable {


	/** @var string */
	private $href = '';

	/** @var string */
	private $rel = '';

	/** @var string */
	private $template = '';

	/** @var string */
	private $type = '';


	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getHref(): string {
		return $this->href;
	}

	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getRel(): string {
		return $this->rel;
	}

	/**
	 * @return string
	 * @since 20.0.0
	 */
	public function getTemplate(): string {
		return $this->template;
	}

	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setHref(string $value): IWebfingerLink {
		$this->href = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setType(string $value): IWebfingerLink {
		$this->type = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setRel(string $value): IWebfingerLink {
		$this->rel = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return IWebfingerLink
	 * @since 20.0.0
	 */
	public function setTemplate(string $value): IWebfingerLink {
		$this->template = $value;

		return $this;
	}


	/**
	 * @return array
	 * @since 20.0.0
	 */
	public function jsonSerialize(): array {
		$data = array_merge(
			[
				'rel'      => $this->getRel(),
				'type'     => $this->getType(),
				'template' => $this->getTemplate(),
				'href'     => $this->getHref()
			], $this->getData()
		);

		return array_filter($data);
	}

}

