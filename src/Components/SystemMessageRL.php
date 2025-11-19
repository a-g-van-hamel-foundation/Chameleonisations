<?php

/**
 * File holding the SystemMessageRL component class
 * This component requires the attribute "data-system-msg" to 
 * be set to a system message.
 * Variant of SystemMessage, but with improved support
 * for ResourceLoader.
 */

namespace Skins\Chameleon\Components;

class SystemMessageRL extends WikitextComponent {

    public static $mPagename = null;
	public static $mNamespace = NS_MEDIAWIKI;
    public $mParserOutput = null;

    /**
	 * @inheritdoc
	 * @return string the HTML code
	 */
	public function getHtml() {
        self::$mPagename = $systemMsg = $this->getDomElement()->getAttribute("data-system-msg");
        if ( $systemMsg === "" ) {
            // Or just hide content?
            $systemMsg = "chameleon-no-system-message";
        }

		return
			$this->indent() . "<!-- $systemMsg (SystemMessageRL) -->" .
			$this->indent() . \Html::openElement( 'div',
				[
					'class' => $this->getClassString(),
					'role'  => 'banner'
                ]
			) .
			$this->indent(1) . wfMessage( $systemMsg )->parse() .
			$this->indent( -1 ) . '</div>' . "\n";
	}

}
