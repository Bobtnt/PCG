<?php

abstract class configObjectAbstract {
	
	const TAB = "\t";
	const NL = "\n";
	const DB_CALLER = 'database_binder::factory()';
	const OUTPUT_FOLDER = 'out';
		
	protected $level=0;
	protected $code;
	
	protected function _append($code=''){
		$indentedCode = str_repeat(self::TAB,$this->level).$code;
		$this->code .= $indentedCode.self::NL;
	}
	
	
}
?>