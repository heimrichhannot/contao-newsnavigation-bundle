# Contao Newsnavigation Bundle

[![Latest Stable Version](https://poser.pugx.org/heimrichhannot/contao-newsnavigation-bundle/v/stable)](https://packagist.org/packages/heimrichhannot/contao-newsnavigation-bundle)

A [contao](https://contao.org/de/) bundle to provide a simple navigation between news articles. It add template variables to go from one news article to the next or the previous article. News article order is calculated by time property.

## Features

* add Template variables to NewsReaderModule to jump between news articles
* option to respect news archives set in NewsReaderModule
* add custom filters via service

## Requirements

* Contao 4.4 (could also work with earlier contao 4 versions)

## Installation

Install via composer:

```
composer require heimrichhannot/contao-newsnavigation-bundle
```

## Usage

### Template variables

To use this extension, you need to output the template variables in your custom news template.

Example:
```
<?php if ($this->previousArticle): ?>
    <a href="{{news_url::<?= $this->previousArticle ?>}}" class="previous">
        <?= $this->previousArticleLabel ?>
    </a>
<?php endif; ?>
```

### Newsarchives

To let the navigation respect only news archives set in NewsReaderModule, you find an option in the module backend.

## Developers

### Template variables

This extension adds following template tags to the news template model, which you can use in your templates:

name                 | description
---------------------|------------
nextArticle          | Next (newer) article id
previousArticle      | Previous (older) article id 
nextArticleLabel     | Next article label ("Next article")
previousArticleLabel | Previous article label ("Previous article")

If you want the news title instead the next/previous article label, see [#1](https://github.com/heimrichhannot/contao-newsnavigation-bundle/issues/1) how to do this.

### Custom filters

The module allow a simple mechanic to add custom filters for the news navigation. You need to set additional filters to the `huh.newsnavigation.newsfilter` before the `parseArticles` Hook (from this module) of the NewsReaderModel is called. You can use following methods:

Examples:
```
// add additional database conditions
System::getContainer()->get('huh.newsnavigation.newsfilter')->addFilter('tl_news.newsCategory=?', 'contao4bundles');
// add additions options
System::getContainer()->get('huh.newsnavigation.newsfilter')->setOption('limit', '5');
```

### Custom filter use

You can use the filter in custom way:

```
$filter = System::getContainer()->get('huh.newsnavigation.newsfilter');

// Creates a new filter object which contains all filters
$newFilter = $filter->createCopy();

// Create a new empty newsfilter instance
$newFilter = new HeimrichHannot\NewsNavigationBundle\NewsFilter\NewsFilter();

// Return the filter rules
$filter->getColumns()
$filter->getValues()
$filter->getOptions()

// Override filter rules
$filter->setColumns();
$filter->setValues();
$filter->setOptions();

// Change or return model
$filter->setModel();
$filter->getModel();

```


