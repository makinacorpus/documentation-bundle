<?php

declare(strict_types=1);

namespace MakinaCorpus\DocumentationBundle;

/**
 * @todo
 *   - convert to interface
 *   - create a cache decorator
 *   - allow users to override (database or other)
 *   - make everything scalable
 *   - provide get(Next|Previous) methods
 */
final class PageIndex
{
    private $map = [];
    private $rel = [];
    private $root;
    private $roots = [];

    /**
     * Default constructor
     */
    public function __construct(string $root, array $array)
    {
        $this->root = $root;

        foreach ($array as $original => $file) {

            $path = $this->normalizePath($file['path'] ?? '');
            if (empty($path)) {
                throw new \InvalidArgumentException(\sprintf("File '%s' has no path", $original));
            }

            // Create missing parent in tree
            // @todo there is probably a way smarted way to do this.
            $parent = $previous = $page = null;
            foreach (\explode('/', $path) as $segment) {
                $parent = $this->normalizePath($parent ? $parent.'/'.$segment : $segment);
                if ($parent === $path) { // Do not include current
                    break;
                }
                if (!isset($this->map[$parent])) {
                    $page = new Page(['root' => $this->root, 'path' => $parent, 'title' => $segment, 'virtual' => true]);
                    if ($previous) {
                        $this->rel[$previous][$parent] = $page;
                    } else {
                        $this->roots[$parent] = $page;
                    }
                    $this->map[$parent] = $page;
                }
                $previous = $parent;
            }

            $page = new Page(['root' => $this->root, 'file' => $original] + $file);
            if ($previous) {
                $this->rel[$previous][$path] = $page;
            } else {
                $this->roots[$path] = $page;
            }
            $this->map[$path] = $page;
        }
    }

    /**
     * Normalize path
     */
    private function normalizePath(string $path): string
    {
        return \trim($path, '/ ');
    }

    /**
     * Get path trail
     */
    public function getTrailBefore(string $path)
    {
        $ret = [];

        $parent = null;
        foreach (\explode('/', $this->normalizePath($path)) as $segment) {
            $parent = $this->normalizePath($parent ? $parent.'/'.$segment : $segment);
            if ($parent === $path) { // Do not include current
                break;
            }
            if (isset($this->map[$parent])) {
                $ret[] = $this->map[$parent];
            }
        }

        return $ret;
    }

    /**
     * @return Page[]
     */
    public function getRootPages(): array
    {
        return $this->roots;
    }

    /**
     * @return Page
     */
    public function get(string $path): Page
    {
        $path = $this->normalizePath($path);
        if (!isset($this->map[$path])) {
            throw new \InvalidArgumentException(\sprintf("File '%s' is not defined", $path));
        }
        return $this->map[$path];
    }

    /**
     * @return Page[]
     */
    public function getChildren(string $path): array
    {
        return $this->rel[$this->normalizePath($path)] ?? [];
    }
}
