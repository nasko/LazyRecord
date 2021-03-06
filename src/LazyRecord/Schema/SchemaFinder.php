<?php
namespace LazyRecord\Schema;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use ReflectionClass;
use RuntimeException;
use IteratorAggregate;
use LazyRecord\ClassUtils;
use LazyRecord\ConfigLoader;

/**
 * Find schema classes from files (or from current runtime)
 *
 * 1. Find SchemaDeclare-based schema class files.
 * 2. Find model-based schema, pass dynamic schema class 
 */
class SchemaFinder
    implements IteratorAggregate
{

    public $paths = array();

    public $classes = array();

    public $config;

    public $logger;

    public function __construct()
    {
        $this->config = ConfigLoader::getInstance();
    }

    public function setLogger($logger) 
    {
        $this->logger = $logger;
    }

    public function in($path)
    {
        $this->paths[] = $path;
    }

    public function addPath($path)
    {
        $this->paths[] = $path;
    }


    public function _loadSchemaFile($file) 
    {
        if ( preg_match( '#Schema.php$#', $file ) ) {
            if ( $this->logger ) {
                $this->logger->info("Loading schema $file");
            }
            require_once $file;
            return;
        }
        return;

        // detect schema by content
        $code = file_get_contents($file);
        $modelPattern = '#' . preg_quote( ltrim($this->config->getBaseModelClass(),'\\') ) . '#';
        if (   preg_match( '#LazyRecord\\\\Schema\\\\SchemaDeclare#ixsm' , $code )
            || preg_match( '#use\s+LazyRecord\\\\Schema#ixsm' , $code ) 
            || preg_match( '/LazyRecord\\\\BaseModel/ixsm' , $code ) 
            || preg_match( $modelPattern, $code ) 
        ) {
            if ( $this->logger ) {
                $this->logger->info("Loading schema $file");
            }
            require_once $file;
        }
    }

    // DEPRECATED
    public function loadFiles() { 
        return $this->find(); 
    }

    public function find()
    {
        if ( empty($this->paths) ) {
            return;
        }

        foreach( $this->paths as $path ) {
            if( is_file($path) ) {
                if ( $this->logger ) {
                    $this->logger->info("Loading schema $file");
                }
                require_once $path;
            } else {
                $rdi   = new RecursiveDirectoryIterator($path);
                $rii   = new RecursiveIteratorIterator($rdi);
                $regex = new RegexIterator($rii, '/^.*Schema\.php$/i', RecursiveRegexIterator::GET_MATCH);
                foreach( $regex as $k => $files ) {
                    foreach( $files as $file ) {
                        // make sure there schema class.
                        // $this->_loadSchemaFile($file);
                        $this->requireFile($file);
                    }
                }
            }
        }
    }

    public function requireFile($file) 
    {
        if ( $this->logger ) {
            $this->logger->info("Loading schema $file");
        }
        return require_once $file;
    }


    /**
     * This method is deprecated.
     */
    public function getSchemaClasses() 
    {
        return $this->getSchemas();
    }


    /**
     * Returns schema objects
     *
     * @return array Schema objects
     */
    public function getSchemas()
    {
        $classes   = ClassUtils::get_declared_schema_classes();
        $schemas   = ClassUtils::expand_schema_classes($classes);
        return $schemas;
        // $dyschemas = ClassUtils::get_declared_dynamic_schema_classes_from_models();
        // return array_merge($schemas, $dyschemas);
    }

    public function getIterator() 
    {
        return $this->getSchemas();
    }
}

