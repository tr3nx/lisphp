<?php

class Tokenizer {
	private $reader;
	private $tokens;
	private $types;

	function __construct($reader, $types) {
		$this->reader = $reader;
		$this->types  = $types;
		$this->tokens = [];

		$this->tokenize();
	}

	private function tokenize() {
		while ( ! $this->reader->eof()) {
			$code = $this->reader->top();

			foreach ($this->types as $token => $reg) {
				$match = preg_match("/^({$reg})/", $code, $matches);
				if ($matches <= 0) return;

				$value = $matches[0];
				$this->reader->skip(strlen($value));

				if ($token === "integer") {
					$value = (int)$value;
				}

				$this->tokens[] = [$token, $value];
				break;
			}
		}
		return $this->tokens;
	}
}
