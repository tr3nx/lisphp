<?php

$code = "(* 1 (+ 2 3 4) 5)";

$tokenTypes = [
	"oparen"  => '\(',
	"cparen"  => '\)',
	"integer" => '[\-]?[0-9]+(?:[\.]?[0-9]+)?',
	"string"  => '\"[^\"]*\"',
	"symbol"  => '[a-zA-Z0-9+=!^%*-\/]+',
];

$reader = new InputReader($code);

$tokens = new Tokenizer($reader, $tokenTypes);
var_dump($tokens);

$parseTree = new Parser($tokens);
var_dump($parseTree);

$generated = new Generator($parseTree);
var_dump($generated);

$evaluated = new Evaluator($parseTree);
var_dump($evaluated);
