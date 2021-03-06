<?php

class Layer {
	
	public $chords = array();

	public function __construct() {
	}

	/**
	 * force deep cloning, so a clone of the measure will contain a clone of all its sub-objects as well
	 * @return [type] [description]
	 */
	public function __clone() {
	    foreach($this as $key => $val) {
	        if (is_object($val) || (is_array($val))) {
	            $this->{$key} = unserialize(serialize($val));
	        }
	    }
	}

	function addNote($note) {
		$chord = new Chord();
		$chord->addNote($note);
		$this->addChord($chord);
	}

	function addChord($chord) {
		$this->chords[] = clone $chord;
	}

	function clear() {
		$this->chords[] = array();
	}

	function toXML() {
		$out = '';
		foreach ($this->chords as $chord) {
			$out .= $chord->toXML();
		}
		return $out;
	}

	/**
	 * transposes all the notes in this layer by $interval
	 * @param  integer  $interval  a signed integer telling how many semitones to transpose up or down
	 * @param  integer  $preferredAlteration  either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
	 * @return  null     
	 */
	public function transpose($interval, $preferredAlteration = 1) {
		foreach ($this->chords as &$chord) {
			$chord->transpose($interval, $preferredAlteration);
		}
	}

	/**
	 * using the 's own Key, will quantize all the notes to be part of a given scale.
	 * If scale is omitted, will use the scale implied by the Key's "mode" property.
	 * @param   $key    a Pitch, like "C" or "G#"
	 * @param   $scale  a Scale object
	 * @return null
	 */
	public function autoTune($key, $scale = null) {
		foreach ($this->chords as &$chord) {
			$chord->autoTune($key, $scale);
		}
	}

	/**
	 * analyze the current layer, and return an array of all the Scales that its notes fit into.
	 * @param  Pitch  $root  if the root is known and we only want to learn about matching modes, provide a Pitch for the root.
	 * @return [type] [description]
	 */
	public function getScales($root = null) {
		$scales = Scale::getScales($this);
	}

	/**
	 * returns an array of Pitch objects, for every pitch of every note in the layer.
	 * @param  boolean  $heightless  if true, will return heightless pitches all mudul to the same octave. Useful for
	 *                              analysis, determining mode etc.
	 * @return array  an array of Pitch objects
	 */
	public function getAllPitches($heightless = false) {
		$pitches = array();
		foreach ($this->chords as $chord) {
			$chordPitches = $chord->getAllPitches($heightless);
			$pitches = array_merge_recursive($pitches, $chordPitches);
		}
		return $pitches;
	}


}
