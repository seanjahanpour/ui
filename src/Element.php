<?php
namespace Jahan\UI;

/**
 * Create base class to represent a html element.
 * Element for PHP HTML Creator
 */
class Element
{
	protected static $counter = 0;  /* To store global count of element to generate unique id */
	public $id;                     /* This instance's id. Generated and stored by constructor, can be overwritten */
	protected $children = [];       /* To store children of this html element */
	protected $tags = [];           /* To store tags for children and scalar values for this element */
	protected $properties = [];     /* Properties of this element */
	protected $container = true;    /* Elements are by default a container. A contrainer is created to store other elements. It can be considered as the top of the tree. */

	protected const VOID_ELEMENTS = ['area','base','br','col','command','embed','hr','img','input','keygen','link','meta','param','source','track','wbr'];

	/*
	@example call:
		new Element(); //blank element
		new Element($class_name);
		new Element('form-gorup');
		new Element($property, $value);
		new Element('name','username');
		new Element($class_name, $property1, $value1, $property2, $value2, $property_n,$value_n);
		new Element('form-inline', 'name','myform' ,'method','post' ,'action','home.php');
		new Element(['property_name' => 'value', 'property2' => 'value2' ]);
	*/
	public function __construct(...$properties)
	{
		$this->id = 'JPHCE' . self::$counter++;	//JPHCE: Jahan PHP Html Creator Element
		if(!empty($properties)) {
			$this->setupProperties($properties);
		}

		$this->bootstrap();
	}

	/**
	 * Setter
	 * 
	 * If value is scalar value, we add that to list of properties for this element. If object is given, object is assumed to be a child.
	 *
	 * @param string $property
	 * @param mix $value
	 */
	public function __set($property, $value)
	{
		if(is_scalar($value)) {
			$this->children[] = $value;
			$this->tags[] = '__scalar__' . $property;
		} elseif(is_object($value)) {
			$value->container = false;
			$this->children[] = $value;
			$this->tags[] = $property;
		}
	}

	public function __call($name, $arguments)
	{
		$obj = new self(...$arguments);
		$obj->container = false;
		$this->children[] = $obj;
		$this->tags[] = $name;

		return $obj;
	}

	public function __toString()
	{
		$ret = '';
		if(!$this->container) {
			/*
			Since name of container element is uknown, and parent is not a Element, we don't know the name of this element to print.
			We don't pring anything when it is a container element. Any element that has a Element parent, parent know the tag name for it,
			so only properties are printed; opening tag and tag name is printed by the parent.
			*/
			$ret .= ' "id"="' . $this->id . '"';
			if( !empty($this->properties) ) foreach($this->properties as $name=>$value) {
				$ret .= " \"$name\"=\"$value\"";
			}
			$ret .= '>';
		}

		if( !empty($this->children) ) foreach($this->children as $index=>$item) {
			if( substr($this->tags[$index],0,10) == '__scalar__') {
				$ret .= $item;
				continue;
			} else {
				$ret .= '<' . $this->tags[$index] . (string) $item;

			}

			// If element is not a void html element, add closing tag
			if(   !in_array( strtolower($this->tags[$index]), self::VOID_ELEMENTS )   ) {
				$ret .= '</' . $this->tags[$index] . '>';
			}
		}

		return $ret;
	}

	protected function setupProperties($properties)
	{
		//ways to accept properties:
		//#1: ('value of class property')
		//#2: ('property','value')
		//#3: ('value of a class','property','value',...more pairs of strings)
		//#4: (['property'=>'value'])
		if(empty($properties)) {
			return;
		} elseif(is_array($properties[0])) {  //handle #4
			foreach($properties[0] as $prop => $value) {
				$this->properties[$prop] = $value;
			}
			return;
		} elseif(count($properties) % 2 == 1) { //handle when 'value of class property' present
			$this->properties['class'] = $properties[0];
			unset($properties[0]);
		}

		if( !empty($properties) ) {  //if other properties present, it would be in format of #2 (#3 will be turned into #2 by unsetting index 0 above)
			$odd = true;
			$property_name = '';
			foreach($properties as $prop) {
				if($odd) {
					$property_name = $prop;
				} else {
					$this->properties[$property_name] = $prop;
				}
				$odd = !$odd;
			}
		}
	}

	/**
	 * Placeholder for drived class implementation. This method is called from __construct when basic properties have been setup.
	 * Use this method in drived classes to do any additional functionality needed in addition to __construct
	 */
	protected function bootstrap()
	{
	}
}