<?php

/**
 * File holding the MainContentNoHeader class
 * Version of MainContent without ContentHeader
 * 
 * @file
 * @ingroup   Skins
 */

namespace Skins\Chameleon\Components;

use Skins\Chameleon\IdRegistry;
use Skins\Chameleon\Components\MainContent;
use Skins\Chameleon\Components\AggregateComponentTrait;
use Skins\Chameleon\Components\Indicators;
use Skins\Chameleon\Components\ContentBody;
use Skins\Chameleon\Components\CategoryLinks;

class MainContentNoHeader extends MainContent {

	use AggregateComponentTrait;

	/**
	 * Builds the HTML code for this component
	 *
	 * @return string the HTML code
	 * @throws \MWException
	 */
	public function getHtml() {
		$idRegistry = IdRegistry::getRegistry();

		$topAnchor = $idRegistry->element( 'a', [ 'id' => 'top' ] );

		$mwBody =
			$topAnchor . $this->indent( 1 ) .
			$this->getSubComponentHtml( Indicators::class, 'Indicators' ) .
			// $this->getSubComponentHtml( ContentHeader::class, 'ContentHeader' ) .
			$this->getSubComponentHtml( ContentBody::class, 'ContentBody' ) .
			$this->getSubComponentHtml( CategoryLinks::class, 'CatLinks' ) .
			$this->indent( - 1 );

		return $this->indent() . '<!-- start the content area -->' .
			$this->indent() . $idRegistry->element(
				'div',
				[ 'id' => 'content', 'class' => 'mw-body ' . $this->getClassString() ],
				$mwBody
			);
	}
}
