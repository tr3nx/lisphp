<?php

class Generator {
	function __construct($tree) {
		$this->generate($tree);
	}

	private function generate($tree) {
		$code = "";
		if (!isset($tree['type'])) {
			if ($tree['type'] === "procedure") {
				$code .= $this->generate_procedure($tree['func'], $tree['args']);
			} else if ($tree['type'] === "quote") {
				$code .= $this->generate_quote($tree['quoted']);
			} else {
				$code .= $this->generate_lambda($tree['argvars'], $tree['body']);
			}
		} else if (is_array($tree)) {
			$code .= implode(" ", $tree);
		} else {
			$code .= $tree;
		}
		return $code;
	}

	private function generate_procedure($rator, $args) {
		$rand = [];
		foreach ($args as $arg) {
			if ( ! isset($arg->type)) {
				$rand[] = $this->generate($arg);
			} else {
				$rand[] = $arg;
			}
		}

		if ( ! is_array($rator)) {
			$rator = $this->generate($rator);
		}

		return "(" . $rator . " " . implode(" ", $rand) . ")";
	}

	private function generate_lambda($argvars, $body) {
		return "(lambda (" . implode("", $argvars) . ") " . $this->generate($body) . ")";
	}

	private function generate_quote($quoted) {
		return "(quote " . $quoted . ")";
	}
}
