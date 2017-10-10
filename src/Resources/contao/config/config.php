<?php
$GLOBALS['TL_HOOKS']['parseArticles']['huh_newsnavigation'] = [
    'HeimrichHannot\NewsNavigationBundle\EventListener\HookListener',
    'addNewsNavigationLinks'
];
