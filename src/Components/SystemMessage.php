<?php
/**
 * File holding the SystemMessage component class
 * This component requires the data attribute ""
 * Top-level container div accepts class and id attributes
 * 
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

		// Whether or not to omit the top-level container div
		$omitWrapperDiv = $this->getDomElement()->getAttribute("data-omit-wrapper");
		if ( $omitWrapperDiv === "true" ) {
			$res = $this->indent() . "<!-- $systemMsg (SystemMessage) -->" .
				$this->indent( 1 ) . wfMessage( $systemMsg )->parse() . 
				$this->indent( -1 ) . "\n";
		} else {
			// Attributes for top-level div
			$divAttributes = [
				"class" => $this->getClassString(),
				"role"  => "banner"
			];
			$idAttr = $this->getDomElement()->getAttribute("id") ?? "";
			if ( $idAttr !== "" ) {
				$divAttributes["id"] = $idAttr;
			}

			$res = $this->indent() . "<!-- $systemMsg (SystemMessage) -->" .
				$this->indent() . \Html::openElement( "div", $divAttributes ) .
				$this->indent(1) . wfMessage( $systemMsg )->parse() .
				$this->indent( -1 ) . "</div>" . "\n";
		}

		return $res;
	}

}
