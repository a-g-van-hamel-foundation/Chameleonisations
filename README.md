# Chameleonisations

Chameleonisations is a small MediaWiki extension to help you extend and customise the [Chameleon skin](https://github.com/ProfessionalWiki/chameleon) in MediaWiki 1.43+. What this currently comes down to is that it makes new component types available on top of the existing ones that ship with Chameleon.

## Installation and configuration
To load the extension, add the following to `LocalSettings.php`

```php
wfLoadExtension( 'Chameleonisations' );
```

If you want to work with Mustache, name your preferred directory for templates in `$wgChameleonMustacheDir`. See below for the details.

## Components

### SystemMessage components

The SystemMessage component lets you use a [system message](https://www.mediawiki.org/wiki/Help:System_message) on your wiki to provide the required content. 

How?

- Create a system message on your wiki, for instance `MediaWiki:Footer` for the footer element. 
- Add the component to your XML layout, adding the name of the page (without namespace prefix) to the `data-system-msg` attribute :

```xml
<component type="SystemMessage" data-system-msg="Footer" />
```

By default, the content of the system message will be wrapped inside a `div` element, which will accept any `class` and `id` attributes you add to the component. You can opt out of this wrapper by adding `div-omit-wrapper="true"` to the component.

### SystemMessageRL components

SystemMessageRL is simply a variant of the SystemMessage component with one crucial difference: it experimentally adds (improved) support for loading ResourceLoader modules. Use this, for instance, if your system message comes with a Semantic MediaWiki query that you need to run in [`@deferred` mode](https://www.semantic-mediawiki.org/wiki/Help:Result_formats_(deferred_mode)). 

This component should be treated as experimental because it is currently unknown if it will have any significant negative impact on server load.

### Mustache components

You can add a new component to your XML layout using a Mustache template and a JSON file containing the data to be passed to your template (https://www.mediawiki.org/wiki/Manual:HTML_templates). Again, as with SystemMessage components, you can add as many Mustache components as you need.

How?

1. Store all Mustache templates and JSON files in a dedicated folder of your MediaWiki installation. For example, we have `search.mustache` and `search.json` (incidentally, both these files are included in the /examples folder) and throw them into `assets/mustache`.
2. Make sure the config setting `$wgChameleonMustacheDir` points to this location, e.g. `$wgChameleonMustacheDir = "assets/mustache";` (no forward slash at the beginning or end). 
3. To your XML layout, add one or multiple Mustache components and set the required data attributes: `data-mustache` (omit the file extension `.mustache`) and `data-json` (do NOT omit the file extension). For example:

```xml
<component type="Mustache" data-mustache="search" data-json="search.json"  />
```

Not yet added is the ability for the site admin to define any specific ResourceLoader modules that a Mustache component may require.

### MainContentNoHeader
Sometimes less is more. The MainContentNoHeader component is virtually a copy of Chameleon's MainContent except that the header/title section has been removed. This allows you to build and customise your own header as part of your wiki content or omit it altogether if your interface happens to require that space for something else.

```xml
<component type="MainContentNoHeader" />
```

### SearchbarCodex

The SearchbarCodex component is an implementation of Codex's TypeaheadSearch input and borrows almost verbatim from the example given on the demo site (https://doc.wikimedia.org/codex/main/components/demos/typeahead-search.html). It was initially just included to demonstrate the possibility of using Vue, or Vue + Codex, to modify the skin, but it may serve as a useful alternative to the default searchbox.

```xml
<component type="SearchbarCodex" />
```

#### SMW
A former version of this extension (unreleased) also contained a search input based on Semantic MediaWiki. Because of the lack of accepted standards and predictability, this was removed but some of this functionality has been further developed for the [Reconciliation API extension](https://github.com/a-g-van-hamel-foundation/ReconciliationAPI). A Chameleon component using a search input based on that extension may be worth considering in the future.

## Credits
Thanks to [Wikibase Solutions](https://github.com/WikibaseSolutions) for leading the way in defining custom components based on system messages and to Morne Alberts (@malberts) for suggesting to me the creation of a separate extension to add complementary features.
