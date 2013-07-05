<?php

use Liriope\Models\Theme;

/**
 * Return the relative path to the theme folder
 */
function theme_folder() {
	return Theme::getFolder();
}