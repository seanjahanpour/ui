<?php
namespace Jahan\UI\BS4;

use Jahan\UI\Element;

class Checkbox extends Text {
	public $type = 'checkbox';
	public $class = 'form-check-input';
	public $div_class = 'form-check';
	public $label_class = 'form-check-label';
	public $checked = false;

	public function toString()
	{
		$input_properties = $this->common_setup();
		
		/*
		<div class="form-check">
			<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
			<label class="form-check-label" for="defaultCheck1">Default checkbox</label>
		</div>
		*/

		$div = new Element($this->div_class);
		
		//help
		$help = $this->make_help();

		if(!empty($help)) {
			$input_properties['aria-describedby'] = $help->id;
		}

		if($this->checked || $this->value == 1) {
			$input_properties['_attribute_'] = 'checked';
		}

		if(!empty($input_properties['value'])) {
			unset($input_properties['value']);
		}
	
		$input = new Element($input_properties);
		
		$div->input = $input;

		$div->label = $this->make_label($input->id);
		
		if(!empty($help)) {
			$div->small = $help;
		}

		return '<div' . (string) $div . '</div>' . PHP_EOL;
	}
}