<?php
namespace Jahan\UI\BS4;

use Jahan\UI\Element;

class Textarea extends Text {
	public $rows = 5;
	public $type = 'textarea';

	public function toString()
	{
		$input_properties = $this->common_setup();
		
		/*
		<div class="form-group">
			<label for="exampleFormControlTextarea1">Example textarea</label>
			<textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
		</div>
		*/

		$div = new Element($this->div_class);
		
		//help
		$help = $this->make_help();

		//input
		if(!empty($help)) {
			$input_properties['aria-describedby'] = $help->id;
		}

		if(!empty($input_properties['value'])) {
			unset($input_properties['value']);
		}
	
		$input = new Element($input_properties);
		$input->_content_ = $this->value;

		$div->label = $this->make_label($input->id);
		
		$div->textarea = $input;

		if(!empty($help)) {
			$div->small = $help;
		}

		return '<div' . (string) $div . '</div>' . PHP_EOL;
	}
}