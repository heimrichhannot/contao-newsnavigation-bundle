# Changelog

## [3.0.0] - 2024-11-28
This release is a major overhaul of this bundle and has breaking changes. Please read the following changes carefully.

- Added: `NewsNavigationFilterEvent` that replaces the old filter service
- Changed: previousArticle and nextArticle properties now lazy loading
- Changed: previousArticle and nextArticle properties are now objects with multiple properties
- Changed: last and next article variable only added to news reader module.
- Deprecated: previousArticleLabel and nextArticleLabel template properties

### Upgrade notes 
- If you used the `huh.newsnavigation.newsfilter` service, you need to update your code to use the new `NewsNavigationFilterEvent` event.
- It is recommended to update the template variable according the docs

## [2.0.2] - 2019-01-14

#### Fixed
* error with symfony 4 due non-public service

## [2.0.1] - 2018-01-10

#### Fixed 
* mistakes in german translation
* missing english translations

## [2.0] - 2017-12-05

### Changed
* refactored `NewsModel` to expandable `NewsFilter` class (NewsModel removed)
* added option to respect news archive in NewsReaderModule
* added codestyle settings (phpcs) and initial unittests


## [1.0.0] - 2017-10-10

Initial version