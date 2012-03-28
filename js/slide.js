/* 
Name: slide.js
Author: Niko Roberts
Date: July 2005
Desc: This script was written by Niko Roberts for controlling div sliding in both IE and Firefox
	To use just create similar code to the following:
		<!-- The sliding div -->
		<div id="sliding_div" style="position:absolute;top:116px;left:154px;display:block;z-index:30;">
		<img src="/images/up.jpg" onClick="javascript:slideup('sliding_div',7,116,20)">
		Click the image to move the image and this text upwards or downwards<br>
		<img src="/images/left.jpg" onClick="javascript:slide('sliding_div',0,154,20)">
		Click the image to move the image and this text left or right<br>
		</div>
	and import this JS file into your html code.
Modification History:
	v2.0.0, Nicholas Roberts, 2-Mar-2009
	- Created functions to also control vertical movement
*/

function IEmoveIn(obName,mid,stepLength,minLeft,maxLeft)
{
	clearTimeout(moveid[mid])
	mid++;
	if (document.getElementById(obName).style.pixelLeft < maxLeft)
	{
		document.getElementById(obName).style.pixelLeft = document.getElementById(obName).style.pixelLeft + stepLength
		moveid[mid]=setTimeout("IEmoveIn(\"" + obName + "\"," + mid + "," + stepLength + "," + minLeft + "," + maxLeft + ")",1)
	}
}

function IEmoveOut(obName,mid,stepLength,minLeft,maxLeft)
{
	clearTimeout(moveid[mid])
	mid++;
	if (document.getElementById(obName).style.pixelLeft > minLeft)
	{
		document.getElementById(obName).style.pixelLeft= document.getElementById(obName).style.pixelLeft - stepLength
		moveid[mid]=setTimeout("IEmoveOut(\"" + obName + "\"," + mid + "," + stepLength + "," + minLeft + "," + maxLeft + ")",1)
	}
}

function MOZmoveIn(obName,mid,stepLength,minLeft,maxLeft)
{
	clearTimeout(moveid[mid])
	mid++;
	if (parseInt(document.getElementById(obName).style.left) < maxLeft)
	{
		document.getElementById(obName).style.left = parseInt(document.getElementById(obName).style.left) + stepLength + "px";
		moveid[mid]=setTimeout("MOZmoveIn(\"" + obName + "\"," + mid + "," + stepLength + "," + minLeft + "," + maxLeft + ")",5)
	}
}

function MOZmoveOut(obName,mid,stepLength,minLeft,maxLeft)
{
	clearTimeout(moveid[mid])
	mid++;
	if (parseInt(document.getElementById(obName).style.left) > minLeft)
	{
		document.getElementById(obName).style.left = parseInt(document.getElementById(obName).style.left) - stepLength + "px";
		moveid[mid]=setTimeout("MOZmoveOut(\"" + obName + "\"," + mid + "," + stepLength + "," + minLeft + "," + maxLeft + ")",5)
	}
}

function IEmoveUp(obName,mid,stepLength,minTop,maxTop)
{
	clearTimeout(moveid[mid])
	mid++;
	if (document.getElementById(obName).style.pixelTop < maxTop)
	{
		document.getElementById(obName).style.pixelTop = document.getElementById(obName).style.pixelTop + stepLength
		moveid[mid]=setTimeout("IEmoveUp(\"" + obName + "\"," + mid + "," + stepLength + "," + minTop + "," + maxTop + ")",1)
	}
}

function IEmoveDown(obName,mid,stepLength,minTop,maxTop)
{
	clearTimeout(moveid[mid])
	mid++;
	if (document.getElementById(obName).style.pixelTop > minTop)
	{
		document.getElementById(obName).style.pixelTop= document.getElementById(obName).style.pixelTop - stepLength
		moveid[mid]=setTimeout("IEmoveDown(\"" + obName + "\"," + mid + "," + stepLength + "," + minTop + "," + maxTop + ")",1)
	}
}

function MOZmoveUp(obName,mid,stepLength,minTop,maxTop)
{
	clearTimeout(moveid[mid])
	mid++;
	if (parseInt(document.getElementById(obName).style.top) < maxTop)
	{
		document.getElementById(obName).style.top = parseInt(document.getElementById(obName).style.top) + stepLength + "px";
		moveid[mid]=setTimeout("MOZmoveUp(\"" + obName + "\"," + mid + "," + stepLength + "," + minTop + "," + maxTop + ")",5)
	}
}

function MOZmoveDown(obName,mid,stepLength,minTop,maxTop)
{
	clearTimeout(moveid[mid])
	mid++;
	if (parseInt(document.getElementById(obName).style.top) > minTop)
	{
		document.getElementById(obName).style.top = parseInt(document.getElementById(obName).style.top) - stepLength + "px";
		moveid[mid]=setTimeout("MOZmoveDown(\"" + obName + "\"," + mid + "," + stepLength + "," + minTop + "," + maxTop + ")",5)
	}
}

function slide(obName,stepLength,minLeft,maxLeft)
{
	if (document.all)
	{
		// if using a IE browser (document.all is set)
		obLeft = document.getElementById(obName).style.pixelLeft
		// Getting the closest multiple of the "step" length to be maximum left value
		closestMaxLeft = (stepLength * parseInt((maxLeft-minLeft) / stepLength)) + minLeft
		closestMaxLeft = (stepLength * parseInt((maxLeft-minLeft) / stepLength)) + minLeft
		
		moveid = new Array()
		mid = parseInt(Math.random()*100)
		moveid[mid]=setTimeout("slide()",50000) // gives something for clearTimeout to clear the first time around
		
		if (obLeft==minLeft)
		{
			IEmoveIn(obName,0,stepLength,minLeft,closestMaxLeft)
			return
		}
		if (obLeft==maxLeft)
		{
			IEmoveOut(obName,0,stepLength,minLeft,closestMaxLeft)
			return
		}
	}
	else
	{
		// If using a non-IE browser such as Firefox (like you should be)
		obLeft = parseInt(document.getElementById(obName).style.left)
		// Getting the closest multiple of the "step" length to be maximum and minimum left values
		if (maxLeft == obLeft)
		{
			closestMinLeft = maxLeft - (stepLength * parseInt((maxLeft-minLeft) / stepLength))
			closestMaxLeft = maxLeft
		}
		if (minLeft == obLeft)
		{
			closestMaxLeft = (stepLength * parseInt((maxLeft-minLeft) / stepLength)) + minLeft
			closestMinLeft = minLeft
		}
		
		moveid = new Array()
		mid = parseInt(Math.random()*100)
		moveid[mid]=setTimeout("slide()",50000) // gives something for clearTimeout to clear the first time around
		
		if (obLeft==closestMinLeft)
		{
			MOZmoveIn(obName,mid,stepLength,closestMinLeft,closestMaxLeft)
			return
		}
		if (obLeft==closestMaxLeft)
		{
			MOZmoveOut(obName,mid,stepLength,closestMinLeft,closestMaxLeft)
			return
		}
	}
}
function slideup(obName,stepLength,minTop,maxTop)
{
	if (document.all)
	{
		// if using a IE browser (document.all is set)
		obTop = document.getElementById(obName).style.pixelTop
		// Getting the closest multiple of the "step" length to be maximum left value
		closestMaxTop = (stepLength * parseInt((maxTop-minTop) / stepLength)) + minTop
		closestMaxTop = (stepLength * parseInt((maxTop-minTop) / stepLength)) + minTop
		
		moveid = new Array()
		mid = parseInt(Math.random()*100)
		moveid[mid]=setTimeout("slideup()",50000) // gives something for clearTimeout to clear the first time around
		
		if (obTop==minTop)
		{
			IEmoveUp(obName,0,stepLength,minTop,closestMaxTop)
			return
		}
		if (obTop==maxTop)
		{
			IEmoveDown(obName,0,stepLength,minTop,closestMaxTop)
			return
		}
	}
	else
	{
		// If using a non-IE browser such as Firefox (like you should be)
		obTop = parseInt(document.getElementById(obName).style.top)
		// Getting the closest multiple of the "step" length to be maximum and minimum left values
		if (maxTop == obTop)
		{
			closestMinTop = maxTop - (stepLength * parseInt((maxTop-minTop) / stepLength))
			closestMaxTop = maxTop
		}
		if (minTop == obTop)
		{
			closestMaxTop = (stepLength * parseInt((maxTop-minTop) / stepLength)) + minTop
			closestMinTop = minTop
		}
		
		moveid = new Array()
		mid = parseInt(Math.random()*100)
		moveid[mid]=setTimeout("slideup()",50000) // gives something for clearTimeout to clear the first time around
		
		if (obTop==closestMinTop)
		{
			MOZmoveUp(obName,mid,stepLength,closestMinTop,closestMaxTop)
			return
		}
		if (obTop==closestMaxTop)
		{
			MOZmoveDown(obName,mid,stepLength,closestMinTop,closestMaxTop)
			return
		}
	}
}