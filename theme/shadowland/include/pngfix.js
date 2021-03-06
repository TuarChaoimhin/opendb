/*
	This script gets the URL of the current page, then extracts the file name
	and path from the URL
*/

var URL = unescape(location.href)	// get current URL in plain ASCII
var xstart = URL.lastIndexOf("/") + 1
var xend = URL.length
var hereName = URL.substring(xstart,xend) // filename
var herePath = URL.substring(0,xstart) // path to file


/*
	Correctly handle PNG transparency in Win IE 5.5 & 6.
	http://homepage.ntlworld.com/bobosola. Updated 18-Jan-2006.
	
	Use in <HEAD> with DEFER keyword wrapped in conditional comments:
	<!--[if lt IE 7]>
		<script src="pngfix.js" defer type="text/javascript"></script>
	<![endif]--> 
*/

var arVersion = navigator.appVersion.split("MSIE")
var version = parseFloat(arVersion[1])
if ((version >= 5.5) && (document.body.filters)) 
{
	for(var i=0; i<document.images.length; i++)
	{
		var img = document.images[i]
		var imgName = img.src.toUpperCase()
		if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
		{
			var imgID = (img.id) ? "id='" + img.id + "' " : ""
			var imgClass = (img.className) ? "class='" + img.className + "' " : ""
			var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "

			// define image style(s)
			var imgStyle = "display:inline-block; vertical-align:middle;" + img.style.cssText
			if (hereName == "login.php") var imgStyle = "display:block; padding-bottom:5px" + img.style.cssText
			if (img == "compulsory.png") var imgStyle = "display:inline-block; vertical-align:top ! important;" + img.style.cssText

			// define alignment
			if (img.align == "left") imgStyle = "float:left;" + imgStyle
			if (img.align == "right") imgStyle = "float:right;" + imgStyle

			// check if img is a link
			if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle

			var strNewHTML = "<span " + imgID + imgClass + imgTitle
			+ " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
			+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
			+ "(src=\'" + img.src + "\', sizingMethod='image'); visibility:visible; \"></span>"
			img.outerHTML = strNewHTML
			i = i-1
		}
	}
}
