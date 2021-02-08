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

	public function toString()
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

		/*
		<div class="form-group">
			<label for="inputid">Label</label>
			<input type="text" class="form-control" id="id" area-describedby="texthelp" placeholder="email">
			<small id="texthelp" class="form-text text-muted help">Explanation and tips.</small>
		</div>
		*/
		$div = $this->make_div($this->div_class);
		
		//help
		if(!empty($this->help)) {
			$help = $this->make_help();
			$help_id = $help->id;
		} else {
			$help_id = '';
		}

		//input
		$input_properties['class'] = $this->class;
		$input_properties['type'] = $this->type;

		if(!empty($this->name)) {
			$input_properties['name'] = $this->name;
		}

		if(!empty($this->placeholder)) {
			$input_properties['placeholder'] = $this->placeholder;
		}

		if(!empty($help_id)) {
			$input_properties['aria-describedby'] = $help_id;
		}
		
		if(!empty($this->value)) {
			$input_properties['value'] = $this->value;
		}

		$input = $this->make_input($input_properties);
		$input_id = $input->id;


		if(!empty($this->label)) {
			$label = $this->make_label($input_id);
			$label->_content_ = $this->label;
			$div->label = $label;
		}
		
		$div->input = $input;

		if(!empty($help_id)) {
			$div->small = $help;
		}

		return '<div' . (string) $div . '</div>';
	}

	protected function make_label($input_id)
	{
		return new Element('for', $input_id);
	}

	protected function make_input(array $input_properties)
	{
		return new Element($input_properties);
	}

	protected function make_help()
	{
		$help = new Element('form-text text-muted');
		$help->_content_ = $this->help;
		return $help;		
	}

	protected function make_div($div_class)
	{
		return new Element($div_class);
	}
}
