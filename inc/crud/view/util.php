<?php

function data_type2html_input( $type, $name, $value ) {

	switch ( $type ) {
		// 数字
		case "int":
		case "real":
		case "3":
		case "8":
			return "<input class='regular-text' type='number' name='$name' value='$value'/>";

		// 日期
		case "date":
		case "10":
			return "<input class='regular-text' type='date' name='$name' value='$value'/>";

		case "time":
		case "11":
			return "<input class='regular-text' type='time' name='$name' value='$value'/>";

		case "datetime":
		case "timestamp":
		case "7":
		case "12":
			return "<input class='regular-text' type='datetime-local' name='$name' value='$value'/>";

		// 长文本
		case "blob":
		case "252":
			return "<textarea class='large-text code' name='$name'>$value</textarea>";
	}

	// 默认 (text)
	return "<input type='text' name='$name' value='" . htmlspecialchars( $value, ENT_QUOTES ) . "'/>";
}