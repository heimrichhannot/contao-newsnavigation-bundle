# Contao Newsnavigation Bundle

[![Latest Stable Version](https://poser.pugx.org/heimrichhannot/contao-newsnavigation-bundle/v/stable)](https://packagist.org/packages/heimrichhannot/contao-newsnavigation-bundle)

A [contao](https://contao.org/de/) bundle to provide a simple navigation between news articles. It add template variables got go from one news article to the next or the previous article. News article order is calculated by time property.

## Requirements

* Contao 4.4 (could also work with earlier contao 4 versions)

## Installation

Install via composer:

```
composer require heimrichhannot/contao-newsnavigation-bundle
```

## Usage

To use this extension, you need to output the template variables in your custom news template.

Example:
```
<?php if ($this->previousArticle): ?>
    <a href="{{news_url::<?= $this->previousArticle ?>}}" class="previous">
        <?= $this->previousArticleLabel ?>
    </a>
<?php endif; ?>
```

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
