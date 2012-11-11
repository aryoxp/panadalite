<?php

class tools {

	public static function arrayToObject($array) {
		if(!is_array($array)) {
			return $array;
		}
		
		$object = (object) null;
		if (is_array($array) && count($array) > 0) {
		  foreach ($array as $name=>$value) {
			 $name = strtolower(trim($name));
			 if (!empty($name)) {
				$object->$name = self::arrayToObject($value);
			 }
		  }
		  return $object;
		}
		else {
		  return FALSE;
		}
	}

}
