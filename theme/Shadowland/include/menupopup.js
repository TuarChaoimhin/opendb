﻿<!--

//		for each option, the value should
//		include a pipe "|" character before each url,
//		to open in a new window, specify a window name
//		urls may be local

function parseNavigation(ob) {
// created by joe crawford october 2002
// http://artlung.com/lab/scripting/dropdown-only-some-new-window/
toBeBrokenDown = ob.options[ob.selectedIndex].value.split("|");

targetWindow = toBeBrokenDown[0];
targetURL    = toBeBrokenDown[1];

	if (targetWindow!=='') {
	// if a new Window name is specified, then it will
	// open in a new Window.
	window.open(targetURL,targetWindow,'toolbar=1,location=1,directories=1,status=1,menubar=1,scrollbars=1,resizable=1,width=800,height=600');
	// if we open a new window, then we have to re-set
	// the select box to the first option
	// which should have no value
	ob.selectedIndex = 0;
		} else {
	// or else it will open in the current window		
	window.open(targetURL,'_top')
	}
}
//-->