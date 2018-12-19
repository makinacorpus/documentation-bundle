<?php

declare(strict_types=1);

namespace MakinaCorpus\DocumentationBundle;

use JoliTypo\Fixer;
use MakinaCorpus\HeaderFixer\Header;

final class PageRenderer
{
    private $relocateOrphans;

    /**
     * Default constructor
     */
    public function __construct(bool $relocateOrphans = true)
    {
        $this->relocateOrphans = $relocateOrphans;
    }

    /**
     * Fix typo
     */
    private function fixTypo(string $input, string $format): string
    {
        if (\class_exists(Fixer::class)) {
            // https://github.com/jolicode/JoliTypo#fr_fr
            $fixer = new Fixer(['Ellipsis', 'Dimension', 'Unit', 'Dash', 'SmartQuotes', 'FrenchNoBreakSpace', 'NoSpaceBeforeComma', 'CurlyQuote', 'Hyphen', 'Trademark']);
            $fixer->setLocale('fr_FR');

            if (false !== \stripos($format, 'html')) {
                return $fixer->fix($input);
            }
            return $fixer->fixString($input);
        }

        return $input;
    }

    /**
     * Render page
     *
     * @param Page $page
     * @param int $decal
     *   H1..6 HTML tag decal offset (1 means starts with H2).
     *
     * @return string
     */
    public function render(Page $page, int $delta = 0): string
    {
        $inputFormat = $page->getFormat();
        $outputFormat = 'text/plain';
        $output = '';

        if (!$page->isVirtual()) {
            switch ($inputFormat) {

                case 'md':
                case 'markdown':
                    $output = (new \Parsedown())->text($page->getContents());
                    $outputFormat = 'html';
                    break;

                case 'html':
                default:
                    $output = $page->getContents();
                    $outputFormat = 'html';
                    break;
            }
        }

        if ($output) {
            if (\class_exists(Header::class)) {
                $output = Header::fixText($output, $page->withTitle() ? $delta + 1 : $delta, $this->relocateOrphans);
            }
            $output = $this->fixTypo($output, $outputFormat);
        }

        return $output;
    }
}
