<?php

/**
 * @see       https://github.com/laminas/laminas-code for the canonical source repository
 * @copyright https://github.com/laminas/laminas-code/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-code/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Code\Scanner;

use Laminas\Code\Annotation\AnnotationManager;
use Laminas\Code\Exception;
use Laminas\Code\NameInformation;

class CachingFileScanner extends FileScanner
{
    /**
     * @var array
     */
    protected static $cache = array();

    /**
     * @var null|FileScanner
     */
    protected $fileScanner = null;

    /**
     * Constructor
     *
     * @param array|null $file
     * @param AnnotationManager $annotationManager
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($file, AnnotationManager $annotationManager = null)
    {
        if (!file_exists($file)) {
            throw new Exception\InvalidArgumentException(sprintf(
                                                             'File "%s" not found', $file
                                                         ));
        }
        $file = realpath($file);

        $cacheId = md5($file) . '/' . ((isset($annotationManager) ? spl_object_hash($annotationManager) : 'no-annotation'));

        if (isset(static::$cache[$cacheId])) {
            $this->fileScanner = static::$cache[$cacheId];
        } else {
            $this->fileScanner       = new FileScanner($file, $annotationManager);
            static::$cache[$cacheId] = $this->fileScanner;
        }
    }

    /**
     * Clear cache
     *
     * @return void
     */
    public static function clearCache()
    {
        static::$cache = array();
    }

    /**
     * Get annotation manager
     *
     * @return AnnotationManager
     */
    public function getAnnotationManager()
    {
        return $this->fileScanner->getAnnotationManager();
    }

    /**
     * Get file
     *
     * @return array|null|string
     */
    public function getFile()
    {
        return $this->fileScanner->getFile();
    }

    /**
     * Get doc comment
     *
     * @return null|string
     */
    public function getDocComment()
    {
        return $this->fileScanner->getDocComment();
    }

    /**
     * Get namespaces
     *
     * @return string[]
     */
    public function getNamespaces()
    {
        return $this->fileScanner->getNamespaces();
    }

    /**
     * Get uses
     *
     * @param null|string $namespace
     * @return array|null
     */
    public function getUses($namespace = null)
    {
        return $this->fileScanner->getUses($namespace);
    }

    /**
     * Get includes
     */
    public function getIncludes()
    {
        return $this->fileScanner->getIncludes();
    }

    /**
     * Get class names
     *
     * @return array
     */
    public function getClassNames()
    {
        return $this->fileScanner->getClassNames();
    }

    /**
     * Get classes
     *
     * @return string[]
     */
    public function getClasses()
    {
        return $this->fileScanner->getClasses();
    }

    /**
     * Get class
     *
     * @param int|string $className
     * @return ClassScanner
     */
    public function getClass($className)
    {
        return $this->fileScanner->getClass($className);
    }

    /**
     * Get class name information
     *
     * @param string $className
     * @return bool|null|NameInformation
     */
    public function getClassNameInformation($className)
    {
        return $this->fileScanner->getClassNameInformation($className);
    }

    /**
     * Get function names
     *
     * @return string[]
     */
    public function getFunctionNames()
    {
        return $this->fileScanner->getFunctionNames();
    }

    /**
     * Get functions
     *
     * @return string[]
     */
    public function getFunctions()
    {
        return $this->fileScanner->getFunctions();
    }
}
