<?php
if ( !isset( $_GET["password"] ) ) {
    echo "There is no 'password' parameter";
	die();
}

$password = $_GET["password"];
if ( isPasswordHasUnappropriateCharacters ( $password ) ) {
	echo "Password contains inappropriate characters or symbols";
	die();
}

echo json_encode( array( 'strength' => getPasswordStrength( $password ) ) );


function getPasswordStrength( $password ){
	$passwordLength = strlen( $password );
	$upperCaseCharactersCount = getUpperCaseCharactersCount( $password );
	$lowerCaseCharactersCount = getLowerCaseCharactersCount( $password );
	$numbersCount = getNumbersCount( $password );
	
	$passwordStrength = 0;
	$passwordStrength += 4 * $passwordLength;
    $passwordStrength += 4 * $numbersCount;
	$passwordStrength += ( $upperCaseCharactersCount > 0 ) 
		? ( ( $passwordLength - $upperCaseCharactersCount ) ^ 2 ) 
		: 0;
	$passwordStrength += ( $lowerCaseCharactersCount > 0 ) 
		? ( ( $passwordLength - $lowerCaseCharactersCount ) ^ 2 ) 
		: 0;
	$passwordStrength -= ( isPasswordHasOnlyCharacters( $password ) )
		? $passwordLength
		: 0;
	$passwordStrength -= ( isPasswordHasOnlyDigits( $password ) )
		? $passwordLength
		: 0;
	$passwordStrength -= getCharacterDuplicatesCount( $password );
	
	return $passwordStrength;
}

function getLowerCaseCharactersCount( $string ){
	return preg_match_all( "/[a-z]/", $string );
}

function getUpperCaseCharactersCount( $string ){
	return preg_match_all( "/[A-Z]/", $string );
}

function getNumbersCount( $string ){
	return preg_match_all( "/[0-9]/", $string );
}

function isPasswordHasUnappropriateCharacters( $password ){
	return preg_match_all( "/[^a-zA-Z0-9]/", $password ) !== 0;
}

function isPasswordHasOnlyDigits( $password ){
	return preg_match_all( "/[^0-9]/", $password ) !== 0;
}

function isPasswordHasOnlyCharacters( $password ){
	return preg_match_all( "/[^a-zA-Z]/", $password ) !== 0;
}

function getCharacterDuplicatesCount( $string ){
	$result = 0;
	$charactersCount = array_count_values( str_split( $string ) );
	
	foreach( $charactersCount as $count ) {
		if ( $count != 1 ) {
			$result += $count;
		}
	}
	return $result;	
}