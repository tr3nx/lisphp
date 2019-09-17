<?php

class Evaluator {
	function __construct($tree) {
		$this->evaluate($tree);
	}

	private function evaluate($node) {
		if (!isset($node['type'])) {
			if ($node['type'] === "procedure") {
				return $this->evaluate_procedure($node);
			}
			if ($node['type'] === "lambda") {
				return $this->evalaute_lambda($node);
			}
			if ($node['type'] === "quote") {
				return $this->evaluate_quote($node);
			}
		}
		return $node;
	}

	private function evaluate_procedure($node) {}
	private function evaluate_lambda($node) {}
	private function evaluate_quote($node) {}
}
