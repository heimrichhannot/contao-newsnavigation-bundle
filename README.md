# Contao Newsnavigation Bundle

A bundle to provide a simple navigation between news articles. It add template variables got go from one news article to the next or the previous article. News article order is calculated by time property.

## Requirements

* Contao 4.4 (could also work with earlier contao 4 versions)

## Installation

Install via composer:

```
composer require heimrichhannot/contao-newsnavigation-bundle
```

## Usage

To use this extension, you need to output the template variables in your custom news template.

## Developers

### Template variables

This extension adds following template tags to the news template model, which you can use in your templates:

name                 | description
---------------------|------------
nextArticle          | Next article id
previousArticle      | Previous article id 
nextArticleLabel     | Next article label ("Next article")
previousArticleLabel | Previous article label ("Previous article")

Usage example:
```
<?php if ($this->previousArticle): ?>
    <a href="{{news_url::<?= $this->previousArticle ?>}}" class="previous">
        <?= $this->previousArticleLabel ?>
    </a>
<?php endif; ?>
```
