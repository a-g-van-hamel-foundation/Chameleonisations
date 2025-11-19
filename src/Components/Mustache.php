<?php

/**
 * File holding the Mustache component class
 */

namespace Skins\Chameleon\Components;

use MediaWiki\MediaWikiServices;
use MediaWiki\Html\TemplateParser;

class Mustache extends Component {

	public function getHtml() {
		// Get directory
		global $IP;
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$templateDir = $IP . "/" . $config->get( "ChameleonMustacheDir" ) ?? null;

		$domEl = $this->getDomElement();
		$templateName = $domEl->getAttribute("data-mustache");
		$jsonFileName = $domEl->getAttribute("data-json");
		// @todo maybe allow for wiki pages, too, rather than files?

		if ( $templateName === "" || $jsonFileName === "" ) {
			return null;
		}

		$jsonContents = file_get_contents( $templateDir . "/" . $jsonFileName );
		if ( !$jsonContents ) {
			return null;
		}
		$data = json_decode( $jsonContents, true );
		if ( gettype($data) !== "array" ) {
			//print_r( "Nothing found in $templateDir/$jsonFileName " );
			return null;
		}

		return
			$this->indent() . "<!-- $templateName (Mustache) -->" .
			$this->indent() . \Html::openElement( "div",
				[
					"class" => $this->getClassString(),
					"role"  => "banner"
                ]
			) . $this->indent(1) .
			$this->processTemplate( $data, $templateName, $templateDir ) .
			$this->indent( -1 ) . "</div>" . "\n";
	}

	public function processTemplate( array $data, string $templateName, string $templateDir ) {
		$templateParser = new TemplateParser( $templateDir );
		$res = $templateParser->processTemplate( $templateName, $data );
		return $res;
	}

}
