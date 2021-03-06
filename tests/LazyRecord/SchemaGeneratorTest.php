<?php

class SchemaGeneratorTest_old extends PHPUnit_Framework_TestCase
{

	public function getLogger()
	{
		return new TestLogger;
	}


	public function test()
	{
        return;
        $finder = new LazyRecord\Schema\SchemaFinder;
        $finder->addPath( 'tests/schema' );
        $finder->find();

		$generator = new LazyRecord\Schema\SchemaGenerator;
		$generator->setLogger( $this->getLogger() );
		$classMap = $generator->generate( $finder->getSchemas() );

		foreach( $classMap as $class => $file ) {
			path_ok( $file , $class );
#  			unlink( $file );
		}

		$author = new \tests\Author;
		$book = new \tests\Book;

        ok( $author );
        ok( $book );

        $schemaProxy = new \tests\AuthorSchemaProxy;
        ok( $schemaProxy->table );
        ok( $schemaProxy->columns );
        ok( $schemaProxy->modelClass );

        ok( $author::model_class );
        ok( $author::schema_proxy_class );
	}

}
