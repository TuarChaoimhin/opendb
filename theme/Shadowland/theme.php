<?php
/*
	Open Media Collectors Database
	Copyright (C) 2001,2006 by Jason Pell

	Shadowland Theme
	Copyright (C) 2008 by Shawn Dunn

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

function thisDir() {

/* * * * * * * * CONFIGURATION OPTIONS * * * * * * * *

	If you've renamed this theme to "default" so that
	the welcome page also uses the Shadowland theme,
	change $defaultTheme from "no" to "yes" - if you
	don't, the navigation drop-down menu will not
	function properly, and images may be missing for
	some versions of Internet Explorer.
*/

$defaultTheme = "no";

/* * * * * * END OF CONFIGURATION OPTIONS  * * * * * */

	if ($defaultTheme == "no")
		return "theme/shadowland/";
	else
		return "theme/default/";
}


/*
	The next TWO functions build the the navigation menu
*/
function get_theme_menu_options_list($options)
{
	$buffer = '';
	if(is_not_empty_array($options)) {
		$buffer .= "\n<form class=\"menu\">";
		$buffer .= "\n<select id=\"navSelect\" onChange=\"parseNavigation(this)\">\n";
/*
		<!-- in each option, the value should               -->
		<!-- include a pipe "|" character before each url,  -->
		<!-- to open in a new window, specify a window name -->
		<!-- urls may be local                              -->
*/
		$buffer .= "\n\t<option value=\"\">Navigation...</option>\n";
		$active_found = FALSE;
		while (list($id,$option_rs) = @each($options)) {
			$buffer .= "\n\t<option value=\"\"></option>\n";
			while (list(,$option_r) = @each($option_rs)) {
				$class = '';
				if(!$active_found && is_menu_option_active($option_r)) {
					$class = ' class="active"';
					$active_found = TRUE;
				}
				$buffer .= get_theme_menu_option($option_r)."</option>\n";
			}
		}
		$buffer .= "\n</select>";
		$buffer .= "\n</form>";
	}
	return $buffer;
}

function get_theme_menu_option($option_r)
{
	$buffer = "\t<option value=\"|".$option_r['url']."\" title=\"".ifempty($option_r['alt'],$option_r['link'])."\"";
	if(starts_with($option_r['target'], 'popup')) {
		$spec = prc_function_spec($option_r['target']);
		if(!is_array($spec['args'])) {
			$spec['args'][0] = '800';
			$spec['args'][1] = '600';
		}
		$buffer .= " onclick=\"popup('".$option_r['url']."','".$spec['args'][0]."','".$spec['args'][1]."'); return false;\"";
	} else if($option_r['target'] == '_new') {
		$buffer = "\t<option value=\"Administration|".$option_r['url']."\" title=\"".ifempty($option_r['alt'],$option_r['link'])."\"";
		$buffer .= "";
	}
	$buffer .= ">".$option_r['link'];
	return $buffer;
}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

function theme_header($pageid, $title, $include_menu, $mode, $user_id)
{
	global $_OPENDB_THEME;
	global $PHP_SELF;
	global $HTTP_VARS;
	global $ADMIN_TYPE;

		if($pageid == 'admin')
			$pageTitle = get_opendb_title_and_version(). " System Admin Tools";
		else if($pageid == 'install')
			$pageTitle = get_opendb_title_and_version(). " Installation";
		else
			$pageTitle = get_opendb_title();

		echo("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">".
		"\n<html>".
		"\n<head>".
		"\n<title>".$pageTitle.(!empty($title)?" - $title":"")."</title>".
		"\n<meta http-equiv=\"Content-Type\" content=\"".get_content_type_charset()."\">".
		"\n<link rel=\"icon\" href=\"".theme_image_src("icon.gif")."\" type=\"image/gif\" />".
		"\n<link rel=\"search\" type=\"application/opensearchdescription+xml\" title=\"".get_opendb_title()." Search\" href=\"./searchplugins.php\">".
		get_theme_css($pageid, $mode).
		get_opendb_rss_feeds_links().
		get_theme_javascript($pageid).
		"\n<!--[if lt IE 7]>".
		"\n\t<script src=\"./". thisDir() ."include/pngfix.js\" defer type=\"text/javascript\"></script>".
		"\n<![endif]-->".
		"\n<script src=\"./". thisDir() ."include/menupopup.js\" language=\"JavaScript\" type=\"text/javascript\"></script>".
		"\n</head>".
		"\n<body>");

	echo("<div id=\"header\">");
	echo("<h1><a href=\"index.php\">".$pageTitle."</a></h1>");
	echo("<div id=\"poweredBy\"><a href=\"http://opendb.iamvegan.net/\" target=\"_blank\">".get_opendb_lang_var('powered_by_site', 'site', get_opendb_title_and_version())."</a></div>");
	echo("\n</div>");
	echo("<div id=\"navigation\">");
	echo("<ul class=\"navLinks\">");

	if($include_menu)
	{
		echo("\n<li>\n\t");
		echo get_theme_menu_options_list(get_menu_options($user_id, $user_type));
		echo("\n</li>");
	}

	if($include_menu) {
		echo("<ul class=\"headerLinks\">");

		$help_page = get_opendb_help_page($pageid);
		if($help_page!=NULL) echo("<li class=\"help\"><a href=\"help.php?page=".$help_page."\" target=\"_new\" title=\"".get_opendb_lang_var('help')."\">".theme_image("help.png")."</a></li>");

		$printable_page_url = get_printable_page_url($pageid);
		if($printable_page_url!=NULL) echo("<li><a href=\"".$printable_page_url."\" target=\"_new\" title=\"".get_opendb_lang_var('printable_version')."\">".theme_image("printable.gif")."</a></li>");
		
		if(is_exists_my_reserve_basket($user_id)) echo("<li><a href=\"borrow.php?op=my_reserve_basket\">".theme_image("basket.png", get_opendb_lang_var('item_reserve_list'))."</a></li>");
		
		if(is_user_granted_permission(PERM_VIEW_LISTINGS)) {
			echo("<li><form class=\"quickSearch\" action=\"listings.php\">".
				"<input type=\"hidden\" name=\"search_list\" value=\"y\">".
				//"<input type=\"hidden\" name=\"attribute_type\" value=\"UPC_ID\">". // Commented out by JP
				//"<input type=\"hidden\" name=\"attr_match\" value=\"partial\">". // Commented out by JP
				//"<input type=\"text\" name=\"attribute_val\" size=\"10\">". // Commented out by JP
				"<input type=\"hidden\" name=\"title_match\" value=\"partial\">".
				"<input type=\"text\" class=\"text\" name=\"title\" size=\"10\">".
				"</form></li>");
		}
		
		if(is_user_granted_permission(PERM_VIEW_ADVANCED_SEARCH)) echo("<li><a href=\"search.php\">".get_opendb_lang_var('advanced')."</a></li>");

		if(strlen($user_id)>0) echo("<li class=\"login\"><a href=\"logout.php\">".get_opendb_lang_var('logout', 'user_id', $user_id)."</a></li>");

		else { echo("<li class=\"login\"><a href=\"login.php?op=login\">".get_opendb_lang_var('login')."</a></li>"); }

		echo("</ul>");
	}
			
	echo("</div>");
	echo("<div id=\"content\" class=\"${pageid}Content\">");

/* // COMMENTED OUT IN LIEU OF DROP-DOWN MENU
	if($include_menu) {
		echo("<div id=\"menu\">");
		echo get_menu_options_list(get_menu_options($user_id));
		echo("\n</div>");
	}
*/
}

function theme_footer($pageid, $user_id)
{
	echo("</div>");

	if($pageid != 'install') echo("<div id=\"footer\"><a href=\"http://opendb.iamvegan.net/\">".get_opendb_lang_var('powered_by_site', 'site', get_opendb_title_and_version())."</a></div>");

	echo("</body></html>");
}

function theme_css_map($pageid)
{
	$themeCssMap = array(
		'borrow'=>array('listings', 'item_display'),
		'item_borrow'=>array('listings', 'item_display'),
		'quick_checkout'=>array('listings', 'item_display'),
		'import'=>array('listings', 'item_display', 'item_input'),
		'item_display'=>array('listings'),
		'item_input'=>array('listings'),
		'user_listing'=>array('listings'),
		'admin'=>array('listings', 'item_input'),
		'export'=>array('item_input'),
		'search'=>array('item_review', 'item_input'),
		'item_review'=>array('item_input'),
		'login'=>array('welcome')
	);
	
	if(isset($themeCssMap)) return $themeCssMap[$pageid]; else return NULL;
}
?>