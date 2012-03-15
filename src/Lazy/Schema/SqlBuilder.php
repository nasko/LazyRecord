<?php
namespace Lazy\Schema;

class SqlBuilder
{
    /**
     * builder object
     */
    public $builder;


    /**
     * should we rebuild (drop existing tables?)
     */
    public $rebuild = true;

    /**
     * xxx: should get the driver type from datasource (defined in model schema)
     */
    function __construct($driverType,$driver)
    {
        $builderClass = get_class($this) . '\\' . ucfirst( $driverType ) . 'Driver';
        $this->builder = new $builderClass( $driver );
        $this->builder->driver = $driver;
        $this->builder->driverType = $driverType;
    }

    public function build(SchemaDeclare $schema)
    {
        $sqls = (array) $this->builder->build( $schema , $this->rebuild );
        return $sqls;
    }

}



