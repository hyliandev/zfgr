<?php

function getTimeIfNull($value){
	return empty($value) ? time() : $value;
}

?>