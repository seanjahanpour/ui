<?php
namespace Jahan\UI\BS4;

use Jahan\UI\Element;

class Text
{
	public $name = '';
	public $label = '';
	public $help = '';
	public $value = '';
	public $type = 'text';
	public $class = 'form-control';
	public $div_class = 'form-group';
	public $help_class = 'form-text text-muted';
	public $label_class = '';
	public $placeholder = '';
	public $required = false;
	public $readonly = false;
	public $visible = true;

	public function __construct(array $properties)
	{
		foreach($properties as $name=>$value) {
			$this->$name = $value;
		}
	}

	public function __toString()
	{
		return $this->toString();
	}

	protected function common_setup()
	{
		$input_properties = [];

		if(!$this->visible) {
			$this->div_class .= ' hidden';
		}

		if($this->readonly) {
			$this->class .= ' disabled';
			$input_properties['_attribute_'] = 'disabled';
		}

		if(!$this->readonly && $this->required) {
			$this->class .= ' required';
			$input_properties['_attribute_'] = 'required';
		}

		$input_properties['class'] = $this->class;
		$input_properties['type'] = $this->type;

		if(!empty($this->name)) {
			$input_properties['name'] = $this->name;
		}

		if(!empty($this->placeholder)) {
			$input_properties['placeholder'] = $this->placeholder;
		}

		if(!empty($this->value)) {
			$input_properties['value'] = $this->value;
		}

		return $input_properties;
	}

	public function toString()
	{
		$input_properties = $this->common_setup();
		
		/*
		<div class="form-group">
			<label for="inputid">Label</label>
			<input type="text" class="form-control" id="id" area-describedby="texthelp" placeholder="email">
			<small id="texthelp" class="form-text text-muted help">Explanation and tips.</small>
		</div>
		*/

		$div = new Element($this->div_class);
		
		$help = $this->make_help();

		//input
		if(!empty($help)) {
			$input_properties['aria-describedby'] = $help->id;
		}
	
		$input = new Element($input_properties);

		$div->label = $this->make_label($input->id);
		
		$div->input = $input;

		if(!empty($help)) {
			$div->small = $help;
		}

		return '<div' . (string) $div . '</div>' . PHP_EOL;
	}

	protected function make_label($input_id)
	{
		if(!empty($this->label)) {
			$label = new Element('for', $input_id);
			$label->_content_ = $this->label;
			if(!empty($this->label_class)) {
				$label->class = $this->label_class;
			}

			return $label;
		}

		return null;
	}

	protected function make_help()
	{
		//help
		if(!empty($this->help)) {
			$help = new Element($this->help_class);
			$help->_content_ = $this->help;
			return $help;
		} else {
			return null;
		}		
	}
}
