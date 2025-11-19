<?php
/**
 * Example of a Chameleon component that uses
 * TypeaheadSearch from the Codex library
 * 
 * For this to work, the layout file must use id = 'mw-typeahead-search'
 * @DG
 * 
 */

// namespace Skins\Chameleon\Components\NavbarHorizontal;
namespace Skins\Chameleon\Components;

use Skins\Chameleon\Components\Component;
use MediaWiki\MediaWikiServices;

class SearchbarCodex extends Component {

	private $mSkinTemplate;
	private $mIndent = 0;
	private $mClasses = [];
	private $mDomElement = null;

	public function getHtml() {
		/*
		$parser = MediaWikiServices::getInstance()->getParserFactory()->create();
		$parserOutput = $parser->getOutput();
		$parserOutput->addModules( [
			'ext.chameleonvue',
			'ext.chameleonvue.components'
		] );
		*/
		/*
		$modulesRegistered = $GLOBALS[ 'wgResourceModules' ];
		print_r( "<pre>" );
		print_r( array_unique (array_keys($modulesRegistered)) );
		print_r( "</pre>" );
		*/

		$res = "<div id='mw-typeahead-search'>...</div>";
		return $res;
	}

	public function getDomElement() {
		return $this->mDomElement;
	}

	/**
	 * @todo Not working on its own
	 * Chameleon uses the SetupAfterCache hook to register component-specific modules
	 * and add them to $GLOBALS[ 'wgResourceModules' ]
	 * even if extension.json is usually what's needed
	 * Cf. 'skin.chameleon.toc', which is also added to SetupAfterCache
	 * @inheritDoc
	 */
	public function getResourceLoaderModules(): array {
		$modules = parent::getResourceLoaderModules();
		$modules[] = 'ext.chameleonvue';
		$modules[] = 'ext.chameleonvue.components';
		return $modules;
	}

}
