<?php

declare(strict_types=1);

// Run in its own PHP process: these doubles keep the regression test independent
// of a Contao installation, database and additional development dependencies.
namespace Contao\CoreBundle\Security\Authentication\Token {
    final class TokenChecker
    {
        public bool $preview = false;

        public function isPreviewMode(): bool
        {
            return $this->preview;
        }
    }
}

namespace Contao {
    class Model
    {
        public int $id = 1;
        public int $time = 1000;
    }

    final class Date
    {
        public static function floorToMinute(): int
        {
            return 1800000000;
        }
    }
}

namespace {
    use Contao\CoreBundle\Security\Authentication\Token\TokenChecker;
    use Contao\Model;
    use HeimrichHannot\NewsNavigationBundle\Filter\Filter;
    use HeimrichHannot\NewsNavigationBundle\Filter\Finder;

    $sourceRoot = $argv[1] ?? dirname(__DIR__, 2);
    require $sourceRoot.'/src/Filter/Filter.php';
    require $sourceRoot.'/src/Filter/Finder.php';

    set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    final class NewsModelDouble extends Model
    {
        public static array $query = [];
        public static ?Model $result = null;

        public static function getTable(): string
        {
            return 'tl_news';
        }

        public static function findOneBy(array $columns, array $values, array $options): ?Model
        {
            self::$query = [$columns, $values, $options];

            return self::$result;
        }
    }

    $expect = static function (bool $condition, string $message): void {
        if (!$condition) {
            throw new RuntimeException($message);
        }
    };

    $tokenChecker = new TokenChecker();
    $finder = new Finder($tokenChecker);
    $filter = (new Filter(new NewsModelDouble()))->setPids([7, 8]);
    $published = "tl_news.published='1' AND (tl_news.start='' OR tl_news.start<=1800000000) AND (tl_news.stop='' OR tl_news.stop>1800000000)";

    foreach (['findPreviousElement' => ['<', 'DESC'], 'findNextElement' => ['>', 'ASC']] as $method => [$operator, $direction]) {
        NewsModelDouble::$result = new NewsModelDouble();
        $expect($finder->$method($filter) === NewsModelDouble::$result, "$method must return the neighbour model");
        $expect(NewsModelDouble::$query === [[$published, 'tl_news.pid IN(7,8)', "tl_news.time $operator ?"], [1000], ['order' => "tl_news.time $direction"]], "$method must work without an order key and preserve archive, publication and time filters");

        NewsModelDouble::$result = null;
        $expect($finder->$method($filter) === null, "$method must return null when there is no neighbour");

        $custom = (clone $filter)->setColumns(['tl_news.featured=?'])->setValues(['1'])->setOptions(['order' => 'tl_news.headline ASC', 'limit' => 3]);
        $finder->$method($custom);
        $expect(NewsModelDouble::$query === [[$published, 'tl_news.pid IN(7,8)', "tl_news.time $operator ?", 'tl_news.featured=?'], [1000, '1'], ['order' => 'tl_news.headline ASC', 'limit' => 3]], "$method must preserve custom filter options and sorting precedence");

        $tokenChecker->preview = true;
        $finder->$method($filter);
        $expect(NewsModelDouble::$query[0] === ['tl_news.pid IN(7,8)', "tl_news.time $operator ?"], "$method must allow unpublished neighbours in preview mode");
        $finder->$method((clone $filter)->setIgnoreFePreview(true));
        $expect(NewsModelDouble::$query[0][0] === $published, "$method must honour ignoreFePreview");
        $tokenChecker->preview = false;
    }

    restore_error_handler();
    echo "PASS FinderTest: previous/next default sorting without warnings, missing neighbours, custom filters and preview visibility\n";
}
