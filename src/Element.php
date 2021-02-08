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
	protected $properties = [];     /* Properties of this element.
						property name of "_attribute_" is special and is printed without name or quotes.
						for example: $properties['_attribute_']='disabled'; will print: <tagname ...  disabled ..></tagname>
					*/
	public $print_tag = true;    /* Elements are by default a container. A contrainer is created to store other elements. It can be considered as the top of the tree. */

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
			$this->setup_properties($properties);
		}

		$this->bootstrap();
	}

	/**
	 * This setter makes statements such as below possible:
	 * 	The word "_content_" is special and values are treated as content for this element and printed between open and close tag.
	 * 	$entity->_content_ = 'hello';   //will print  <sometag ...>hello</sometag>
	 * 	$entity->label = new Element('label');
	 * 	$entity->style = 'color:red';  // will print <sometag ... style="color:red"..>...</sometag>
	 * 
	 * If value is scalar value, we add that to list of properties for this element. If object is given, object is assumed to be a child.
	 *
	 * @param string $property
	 * @param mix $value
	 */
	public function __set($property, $value)
	{
		if(is_scalar($value)) {
			if($property == '_content_') {
				$this->children[] = $value;
				$this->tags[] = '__scalar__';				
			} else {
				$this->add_property($property, $value);
			}
		} elseif(is_object($value)) {
			$this->children[] = $value;
			$this->tags[] = $property;
		}
	}

	public function add_property($property, $value)
	{
		$this->properties[$property] = $value;
	}

	/**
	 * This is to make statement like below possible:
	 * 	$obj->div('form-group');
	 *
	 * @param string $name
	 * @param array|string $arguments
	 * @return Element
	 */
	public function __call(string $name, $arguments) : object
	{
		$obj = new self(...$arguments);
		$this->children[] = $obj;
		$this->tags[] = $name;

		return $obj;
	}

	public function __toString()
	{
		return $this->toString();
	}

	public function toString()
	{
		$ret = '';
		if($this->print_tag) {
			/*
			Since name of container element is uknown, opening tag is partially printed. Any element that has a Element parent, parent know the tag name and will print before calling this function,
			*/
			$ret .= ' id="' . $this->id . '"';
			if( !empty($this->properties) ) foreach($this->properties as $name=>$value) {
				if($name == '_attribute_') {
					$ret .= " $value";
				} else {
					$ret .= " $name=\"$value\"";
				}
			}
			$ret .= '>';

			//if element doesn't have any children, don't pring new line character for better formatting.
			if(!empty($this->children)) {
				$tmp_arrray_count = array_count_values($this->tags);
				
				//are there real childre, or is it only content?
				if(!empty($tmp_arrray_count['__scalar__'])  &&  $tmp_arrray_count['__scalar__'] == count($this->tags)) {
					//no children
				} else {
					$ret .= PHP_EOL;
				}
			}
		}

		if( !empty($this->children) ) foreach($this->children as $index=>$item) {
			if( $this->tags[$index] == '__scalar__') {
				//contents
				$ret .= $item;
				continue;
			} else {
				$ret .=  "\t<" . $this->tags[$index] . (string) $item;
			}

			// If element is not a void html element, add closing tag, otherwise just new line character
			if(   !in_array( strtolower($this->tags[$index]), self::VOID_ELEMENTS )   ) {
				$ret .= '</' . $this->tags[$index] . '>' . PHP_EOL;
			} else {
				$ret .= PHP_EOL;
			}
		}

		return $ret;
	}

	protected function setup_properties($properties)
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

	/*
	* create and return any javascript [or jQuery if used in this project] for this input element in form of a string.
	*/
	public function javascript()
	{
		return '';
	}
}