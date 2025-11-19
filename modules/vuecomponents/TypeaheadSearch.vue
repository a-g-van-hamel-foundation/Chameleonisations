<template>
	<div>
		<cdx-typeahead-search
			id="typeahead-search-default"
			form-action=formAction
			button-label="Search"
			search-results-label="Search results"
			:initial-input-value="initialInputValue"
			:search-results="searchResults"
			:search-footer-url="searchFooterUrl"
			:show-thumbnail="true"
			:highlight-query="true"
			placeholder="Search the website"
			@input="onInput"
		>
			<template #search-footer-text="{ searchQuery }">
				Search the website for pages containing
				<strong class="cdx-typeahead-search__search-footer__query">
					{{ searchQuery }}
				</strong>
			</template>
		</cdx-typeahead-search>
	</div>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxTypeaheadSearch } = require( '@wikimedia/codex' );

module.exports = defineComponent( {
	name: 'TypeaheadSearchInitialValue',
	components: { CdxTypeaheadSearch },
	props: {
		/**
		 * For demo purposes, the initial input value "Color" has been passed in
		 * as a prop.
		 */
		initialInputValue: {
			type: String,
			default: ''
		}
	},
	setup() {
		const server = mw.config.get("wgServer");
		const scriptPath = mw.config.get("wgScriptPath");
		const articlePath = mw.config.get("wgArticlePath").replace("/$1", "");
		const formAction = ref( server + scriptPath + "/index.php" );

		const searchResults = ref( [] );
		const searchFooterUrl = ref( '' );
		const currentSearchTerm = ref( '' );

		function onInput( value ) {
			currentSearchTerm.value = value;

			if ( !value || value === '' ) {
				searchResults.value = [];
				searchFooterUrl.value = '';
				return;
			}

			/**
			 * Format search results for consumption by TypeaheadSearch.
			 *
			 * @param pages
			 * @return
			 */
			function adaptApiResponse( pages ) {
				return pages.map( ( { id, key, title, description, thumbnail } ) => ( {
					label: title,
					value: id,
					description: description,
					url: server + articlePath + `/${ encodeURIComponent( key ) }`,
					thumbnail: thumbnail ? {
						url: thumbnail.url,
						width: thumbnail.width,
						height: thumbnail.height
					} : undefined
				} ) );
			}

			fetch(
				server + scriptPath + `/rest.php/v1/search/title?q=${ encodeURIComponent( value ) }&limit=10&`
			).then( ( resp ) => resp.json() )
				.then( ( data ) => {
					if ( currentSearchTerm.value === value ) {
						searchResults.value = data.pages && data.pages.length > 0 ?
							adaptApiResponse( data.pages ) :
							[];

						searchFooterUrl.value = server + scriptPath + `/index.php?title=Special%3ASearch&fulltext=1&search=${ encodeURIComponent( value ) }`;

					}
				} ).catch( () => {
					searchResults.value = [];
					searchFooterUrl.value = '';
				} );
		}

		return {
			formAction,
			searchResults,
			searchFooterUrl,
			onInput
		};
	}
} );
</script>