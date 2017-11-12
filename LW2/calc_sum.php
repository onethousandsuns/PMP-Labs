<?php
$queryParams = [];
parse_str( $_SERVER['QUERY_STRING'], $queryParams );

$resultMessage = "";
if ( count( $queryParams ) !== 3 ){
	$resultMessage = "Wrong amount of parameters. Must be 3.";
} else if ( !isParameterExists( "arg1" ) 
	|| !isParameterExists( "arg2" ) 
	|| !isParameterExists( "operation" ) ) {
	$resultMessage = "Some of parameters are wrong. Must be 'arg1', 'arg2' and 'operation'.";
} else if ( !isOperationValid ( $_GET["operation"] ) ){
	$resultMessage = "Unknown operation type";
} else if ( !isParameterCanBeConvertedToNumber( "arg1" ) 
	|| !isParameterCanBeConvertedToNumber( "arg2" ) ) {
	$resultMessage = "Parameters 'arg1' and 'arg2' must contain numeric value";
} else {
	$arg1 = intval( $_GET["arg1"] );
	$arg2 = intval( $_GET["arg2"] );
	$operation = $_GET["operation"];
	
	switch ( $operation ) {
		case "add":
			$resultMessage = $arg1 + $arg2;
		case "sub":
			$resultMessage = $arg1 - $arg2;
		case "mul":
			$resultMessage = $arg1 * $arg2;
		case "div":
			if ( $arg2 === 0 ){
				$resultMessage = "Zero division attempt";
			} else {
				$resultMessage = $arg1 / $arg2;
			}
	}
}

echo $resultMessage;

function isOperationValid( $operation ){
	$operations = array( 'add', 'sub', 'mul', 'div' );
	return in_array( $operation, $operations );
}

function isParameterExists( $parameter ){
	return isset( $_GET[$parameter] );
}

function isParameterCanBeConvertedToNumber( $parameter ){
	return is_numeric( $_GET[$parameter] );
}
