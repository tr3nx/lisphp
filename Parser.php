<?php

class Parser {
	private $tokens;

	function __construct($tokens) {
		$this->tokens = $tokens;

		$this->parse_expr();
	}

	private function parse_expr() {
		if ($this->peek() === "oparen") {
			return $this->parse_list();
		}
		return $this->parse_atomic();
	}

	private function parse_atomic() {
		if ($this->peek() === "integer") {
			return $this->consume("integer")[1];
		}
		if ($this->peek() === "string") {
			return $this->consume("string")[1];
		}
		return $this->consume("symbol")[1];
	}

	private function parse_list() {
		if ($this->peek(1) === "symbol") {
			if ($this->tokens[1][1] === "lambda") {
				return $this->parse_lambda();
			}
			if ($this->tokens[1][1] === "quote") {
				return $this->parse_quote();
			}
		}
		return $this->parse_procedure();
	}

	private function parse_lambda() {
		$this->consume("oparen");
		$this->consume("symbol");
		$this->consume("oparen");

		$argvars = [];
		while ($this->peek() !== "cparen") {
			$argvars[] = $this->consume("symbol")[1];
		}

		$this->consume("cparen");
		$body = $this->parse_expr();
		$this->consume("cparen");

		return [
			'type'    => 'lambda',
			'argvars' => $argvars,
			'body'    => $body
		];
	}

	private function parse_procedure() {
		$this->consume("oparen");

		$rator = null;
		if ($this->peek() === "oparen") {
			$rator = $this->parse_list();
		} else {
			$rator = $this->consume("symbol")[1];
		}

		$rand = [];
		while ($this->peek() !== "cparen") {
			$rand[] = $this->parse_expr();
		}

		$this->consume("cparen");

		return [
			'type' => 'procedure',
			'func' => $rator,
			'args' => $rand
		];
	}

	private function parse_quote() {
		$this->consume("oparen");
		$this->consume("symbol");

		$quoted = [];
		$depth = 0;
		while (true) {
			if ($this->peek() === "cparen" && $depth === 0) break;
			if ($this->peek() === "oparen") $depth++;
			if ($this->peek() === "cparen") $depth--;
			$quoted[] = array_shift($this->tokens)[1];
		}

		$this->consume("cparen");

		$quoted = str_replace(" )", "", str_replace("( ", "", implode(" ", $quoted)));

		return [
			'type'   => 'quote',
			'quoted' => $quoted
		];
	}

	private function peek($offset=0) {
		if (!isset($this->tokens[$offset])) {
			throw new Exception("Syntax Error: forget a closing paren?");
		}
		return $this->tokens[$offset][0];
	}

	private function consume($expected) {
		$token = array_shift($this->tokens);
		if ($token[0] !== $expected) {
			throw new Exception("Syntax Error: [".count($tokens)."] excepted '{$excepted}' but got ".$token[0]);
		}
		return $token;
	}
}
