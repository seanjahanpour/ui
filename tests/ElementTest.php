<?php
declare(strict_types=1);
namespace Test;

use Jahan\UI\Element;

use function PHPUnit\Framework\assertEquals;

final class ElementTest extends BaseCase
{
	protected function setup(): void
	{
		//runs before every test
	}

	public static function setUpBeforeClass(): void
	{
		//runs once before tests
	}

	public static function tearDownAfterClass(): void
	{
		//runs once after tests
	}

	public function test_element_initiated_with_class_only()
	{
		$test = new Element('test-class');

		assertEquals(' "id"="JPHCE0" "class"="test-class">', (string)$test);
	}


	public function test_element_initiated_with_class_and_properties()
	{
		$test = new Element('data-test', '10');

		assertEquals(' "id"="JPHCE1" "data-test"="10">', (string)$test);


		$test = new Element('test-class', 'data-test', '10');

		assertEquals(' "id"="JPHCE2" "class"="test-class" "data-test"="10">', (string)$test);


		$test = new Element('test-class', 'data-test', '10', 'placeholder', 'happy');

		assertEquals(' "id"="JPHCE3" "class"="test-class" "data-test"="10" "placeholder"="happy">', (string) $test);
		
		
		$test = new Element('data-test', '10', 'placeholder', 'happy');

		assertEquals(' "id"="JPHCE4" "data-test"="10" "placeholder"="happy">', (string) $test);


		$test = new Element(['property_name' => 'value', 'property2' => 'value2' ]);

		assertEquals(' "id"="JPHCE5" "property_name"="value" "property2"="value2">', (string) $test);
	}
}