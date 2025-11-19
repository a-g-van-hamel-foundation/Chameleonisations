<?php
/**
 * File holding the SystemMessage component class
 * This component requires the data attribute ""
 */

namespace Skins\Chameleon\Components;

class SystemMessage extends Component {

	/**
	 * Builds the HTML code for this component
	 *
	 * @return String the HTML code
	 */

	public function getHtml() {
        // required
        $systemMsg = $this->getDomElement()->getAttribute("data-system-msg");
        if ( $systemMsg === "" ) {
            // Or just hide content?
            $systemMsg = "chameleon-no-system-message";
        }

		return
			$this->indent() . "<!-- $systemMsg (SystemMessage) -->" .
			$this->indent() . \Html::openElement( "div",
				[
					"class" => $this->getClassString(),
					"role"  => "banner"
                ]
			) .
			$this->indent(1) . wfMessage( $systemMsg )->parse() .
			$this->indent( -1 ) . "</div>" . "\n";
	}

}
