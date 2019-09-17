<?php

class InputReader {
	private $input;
	private $pos;

	function __construct(string $input) {
		$this->input = $input;
		$this->pos = 0;
	}

	public function top() {
		return $this->eof()
			? ""
			: $this->peek() === " "
				? $this->skip()->top()
				: substr($this->input, $this->pos);
	}

	public function read($amount=1) {
		if ($this->eof()) return "";
		$r = "";
		foreach (range(0, $amount) as $i) {
			$r .= $this->next();
		}
		return $r;
	}

	public function next() {
		return $this->eof()
			? ""
			: $this->input[$this->pos++];
	}

	public function skip($amount=1) {
		$this->pos += $amount;
		return $this;
	}

	public function peek($offset=0) {
		return $this->input[$this->pos + $offset];
	}

	public function eof() {
		return $this->pos >= count($this->input)
			|| $this->peek() === "";
	}
}
