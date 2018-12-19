<?php

declare(strict_types=1);

namespace MakinaCorpus\DocumentationBundle;

final class Page
{
    private $file;
    private $format;
    private $path;
    private $root;
    private $skipTitle;
    private $title;
    private $virtual;
    private $withToc;

    /**
     * Default constructor
     */
    public function __construct(array $options)
    {
        $this->file = $options['file'] ?? null;
        $this->format = $options['format'] ?? null;
        $this->path = $options['path'] ?? null;
        $this->root = $options['root'] ?? '/dev/null';
        $this->skipTitle = (bool)($options['skip_title'] ?? false);
        $this->title = $options['title'] ?? null;
        $this->virtual = (bool)($options['virtual'] ?? !$this->file);
        $this->withToc = (bool)($options['with_toc'] ?? false); // @todo should default to true
    }

    /**
     * Guess file format
     */
    private function guessFormat(): string
    {
        // @todo should we handle this using mimetypes instead?
        if ($this->file && ($pos = \strrpos($this->file, '.'))) {
            return \substr($this->file, $pos + 1);
        }

        return 'text/plain';
    }

    /**
     * Is page title included in text
     */
    public function withTitle(): bool
    {
        return !$this->skipTitle;
    }

    /**
     * Display table of contents
     */
    public function withToc(): bool
    {
        return $this->withToc;
    }

    /**
     * Virtual pages don't have an associated file
     */
    public function isVirtual(): bool
    {
        return $this->virtual;
    }

    /**
     * Get file format
     */
    public function getFormat(): string
    {
        return $this->format ?? ($this->format = $this->guessFormat());
    }

    /**
     * Get relative file path
     */
    public function getFile(): string
    {
        return $this->file ?? '';
    }

    /**
     * Get absolute file path
     */
    public function getAbsoluteFile(): string
    {
        return $this->file ? $this->root.'/'.$this->file : '';
    }

    /**
     * Get documentation path
     */
    public function getPath(): string
    {
        return $this->path ?? '';
    }

    /**
     * Get page title
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * Get file contents if any
     */
    public function getContents(): string
    {
        return $this->file ? \file_get_contents($this->getAbsoluteFile()) : '';
    }
}
