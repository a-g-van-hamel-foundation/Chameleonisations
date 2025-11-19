<?php
/**
 * File containing the WikitextComponent class
 * This file is part of the MediaWiki skin Chameleon.
 * 
 * @todo See which methods can be simply inherited
 * (no need to overwrite them with identical ones)
 * @todo don't hardcode language 'en'
 * 
 * @file
 * @ingroup Skins
 */

namespace Skins\Chameleon\Components;

use Skins\Chameleon\ChameleonTemplate;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\Parser\ParserOptions;

/**
 * WikitextComponent class
 * @ingroup Skins
 */
abstract class WikitextComponent extends Component {

	private $mSkinTemplate;
	private $mIndent = 0;

	/** @var $array */
	private $mClasses = [];

	private $mDomElement = null;

	// Must be exposed to child class
	public $mParserOutput;
	private $mPagename = null;
	private $mNamespace = null;

	/**
	 * @param ChameleonTemplate $template
	 * @param \DOMElement|null $domElement
	 * @param int $indent
	 *
	 * @throws \MWException
	 */
	public function __construct( ChameleonTemplate $template, \DOMElement $domElement = null,
		$indent = 0 ) {
		$this->mSkinTemplate = $template;
		$this->mIndent       = (int)$indent;
		$this->mDomElement   = $domElement;

		if ( $domElement !== null ) {
			$this->addClasses( $domElement->getAttribute( 'class' ) );
		}

		// Get values from child
		$this->mPagename = static::$mPagename;
		$this->mNamespace = static::$mNamespace;
		if ( $this->mPagename !== null && $this->mNamespace !== null ) {
			$this->getParserOutputFromPage(
				$this->mPagename,$this->mNamespace
			);
		}
	}

	/**
	 * Sets the class string that should be assigned to the top-level html element of this component
	 *
	 * @param string | array | null $classes
	 *
	 * @throws \MWException
	 */
	public function setClasses( $classes ) {
		$this->mClasses = [];
		$this->addClasses( $classes );
	}

	/**
	 * Adds the given class to the class string that should be assigned to the top-level html
	 * element of this component
	 *
	 * @param string | array | null $classes
	 *
	 * @throws \MWException
	 */
	public function addClasses( $classes ): void {
		$classesArray = $this->transformClassesToArray( $classes );

		if ( !empty( $classesArray ) ) {
			$classesArray = array_combine( $classesArray, $classesArray );
			$this->mClasses = array_merge( $this->mClasses, $classesArray );
		}
	}

	/**
	 * {@inherit} transformClassesToArray
	 */
	/**
	 * @inheritDoc
	 * @param string | array | null $classes
	 *
	 * @return array
	 * @throws \MWException
	 */
	protected function transformClassesToArray( $classes ) {
		if ( empty( $classes ) ) {
			return [];
		} elseif ( is_array( $classes ) ) {
			return $classes;
		} elseif ( is_string( $classes ) ) {
			return explode( ' ', $classes );
		} else {
			throw new \MWException( __METHOD__ . ': Expected String or Array; ' . gettype( $classes ) .
				' given.' );
		}
	}

	/**
	 * @return ChameleonTemplate
	 */
	public function getSkinTemplate() {
		return $this->mSkinTemplate;
	}

	/**
	 * @since 1.1
	 * @return \Skins\Chameleon\Chameleon
	 */
	public function getSkin() {
		return $this->mSkinTemplate->getSkin();
	}

	/**
	 * @inheritDoc
	 *
	 * @return int
	 */
	public function getIndent() {
		return $this->mIndent;
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function getClassString() {
		return implode( ' ', $this->mClasses );
	}

	/**
	 * @inheritDoc
	 *
	 * @param string | array | null $classes
	 *
	 * @throws \MWException
	 */
	public function removeClasses( $classes ) {
		$classesArray = $this->transformClassesToArray( $classes );

		$this->mClasses = array_diff( $this->mClasses, $classesArray );
	}

	/**
	 * @inheritDoc
	 *
	 * @return \DOMElement
	 */
	public function getDomElement() {
		return $this->mDomElement;
	}

	/**
	 * Builds the HTML code for this component
	 *
	 * @return String the HTML code
	 */
	abstract public function getHtml();

    /**
	 * @return string[] the resource loader modules needed by this component
	 */
	public function getResourceLoaderModules() {
		$modules = parent::getResourceLoaderModules();
		$parserOutput = $this->mParserOutput;
		// self::getParserOutputFromPage( $this->mPagename, $this->mNamespace );
		$modulesRequired = $parserOutput !== null ? $parserOutput->getModules() : [];
		foreach ( $modulesRequired as $module ) {
			$modules[] = $module;
		}
		return $modules;
	}

	/**
	 * @inheritDoc
	 *
	 * @param int $indent
	 * @return string
	 * @throws \MWException
	 */
	protected function indent( $indent = 0 ) {
		$this->mIndent += (int)$indent;

		if ( $this->mIndent < 0 ) {
			throw new \MWException( 'Attempted HTML indentation of ' . $this->mIndent );
		}

		return "\n" . str_repeat( "\t", $this->mIndent );
	}

	/**
	 * @inheritDoc
	 */
	protected function getAttribute( $attributeName, $default = '' ) {
		$element = $this->getDomElement();

		if ( is_a( $element, 'DOMElement' ) && $element->hasAttribute( $attributeName ) ) {
			return $element->getAttribute( $attributeName );
		}

		return $default;
	}

	/**
	 * ....
	 */
	public function getParserOutputFromPage( $mPagename, $mNamespace ) {
		$parser = MediaWikiServices::getInstance()->getParserFactory()->create();
		$title = Title::newFromText( $mPagename, $mNamespace );
		$pageIdentity = $title->toPageIdentity();
		// Maybe revise this:
		$parserOptions = new ParserOptions(
			User::newFromId( 0 ),
			// @todo language
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' )
		);
		$wikiPage = new \WikiPage( $pageIdentity );
		if ( !$wikiPage->exists() ) {
			//
		}
		$content = $wikiPage->getContent();
		if ( $content == null ) {
			return;
		}
		$parsed = $parser->parse( $content->getText(), $pageIdentity, $parserOptions );
		$parserOutput = $parser->getOutput();
		$this->mParserOutput = $parserOutput;
	}

}
