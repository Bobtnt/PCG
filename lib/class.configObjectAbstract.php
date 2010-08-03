<?php
/**
 * This file is a part of php class generator (PCG) apps.
 *
 * licence: Cecill http://www.cecill.info/licences/Licence_CeCILL_V1.1-US.html
 * author: Cyril Janssens
 * $Id$
 */
/**
 * Enter description here...
 *
 */
abstract class configObjectAbstract {

	const TAB = "\t";
	const NL = "\n";
	const DB_CALLER = 'database_binder::factory()';
	const OUTPUT_FOLDER = 'out';

	protected $level=0; // deprecated
	protected $code;
	protected $internalLevel=0;


	protected function _append($code=''){

		if(preg_match('#\}$#', trim($code))){
			$this->internalLevel--;
		}

		$indentedCode = str_repeat(self::TAB,$this->internalLevel).$code;
		$this->code .= $indentedCode.self::NL;

		if(preg_match('#\{$#', trim($code))){
			$this->internalLevel++;
		}
		//usefull for debuging
		return $indentedCode;
	}


}
?>