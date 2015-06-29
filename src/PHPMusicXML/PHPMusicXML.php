<?php
namespace ianring;

require_once('classes/Score.php');
require_once('classes/Measure.php');
require_once('classes/Note.php');
require_once('classes/Direction.php');
require_once('classes/Part.php');


class PHPMusicXML {

	function __construct() {
	}

	/**
	 * read a MusicXML document and return it parsed as a PHP object
	 * @param  [type] $strXML [description]
	 * @return [type]         [description]
	 */
	function fromXML($strXML) {
		return $score;
	}

	function toPNG() {
		// todo
	}

	function toPDF() {
		// todo
	}


	/**
	 * accepts an array such as x=>['a'=>'b','c'=>5], and outputs <x a="b"><c>5</c></x>
	 * @param  [type] $obj           [description]
	 * @param  [type] $attributeKeys [description]
	 * @return [type]                [description]
	 */
	public function renderXML($obj, $elemName, $attributeKeys) {
		$out = '';
		$out .= '<';
		$out .= $elemName;
		foreach ($attributeKeys as $attributeKey) {
			if (isset($obj[$attributeKey])) {
				$out .= ' ' . $attributeKey . '="' . $obj[$attributeKey] . '"';
				unset($obj[$attributeKey]);
			}
		}
		$out .= '>';
		foreach ($obj as $e => $v) {
			if (is_array($v)) {
				$out .= $this->renderXML($v, $e, $attributeKeys);
			} else {
				$out .= '<' . $e . '>' . $v . '</' . $e . '>';
			}
		}
		$out .= '<' . $elemName . '>';
		return $out;
	}

}
