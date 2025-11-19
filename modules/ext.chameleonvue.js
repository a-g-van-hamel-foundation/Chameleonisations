"use strict";

( function () {
	const Vue = require( "vue" );
	const App = require( 'ext.chameleonvue.components' ).TypeaheadSearch;
	Vue.createMwApp( App ).mount( "#mw-typeahead-search" );
}() );
