<?php

/*
Plugin Name: Kavychker
Plugin URI: http://mbyte.org.ua/
Description: Replace plain text symbols with html entities (uses modified <a href="http://textus.ru">Kavychker&copy;</a> code) (wp2.5 and higher)
Version: 1.0
Author: mByte
Author URI: http://mbyte.org.ua/personal/
*/

/*  Copyright 2007-2009  mByte  (email : mbyte@mbyte.org.ua)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    
*/

add_action('admin_head', 'kavychker_add');
add_action('admin_footer', 'kavychker_footer');

function kavychker_add() {
if (preg_match("/page[\-new]+\.php/",$_SERVER['REQUEST_URI']) || preg_match("/post[\-new]+\.php/",$_SERVER['REQUEST_URI'])) {

?><script type="text/javascript">

function ht()
{
document.post.content.focus();
s = " "+document.post.content.value;
$before=s.length;

// кавычки
if (document.post.what.value=="1")
{
i=0;

// put in array
a=s.match(/<([^>]*)>/g)

r=/<([^>]*)>/;
while (r.test(s))
{
i++;
s = s.replace(r, "\x01"+i+"\x02");

}

// wordfix
s = s.replace(/З/g, "\"");
s = s.replace(/И/g, "\"");
s = s.replace(/Т/g, "\"");
s = s.replace(/У/g, "\"");
s = s.replace(/Ч/g, "\"");
s = s.replace(/Й/g, "...");
s = s.replace(/Р/g, "-");
s = s.replace(/С/g, "-");

// kavychking
s = s.replace(/([\x01-(\s\"])(\")([^\"]{1,})([^\s\"(])(\")/g, "$1З$3$4И");


// kavychking in kavychking
if (/"/.test(s))
{
s = s.replace(/([\x01(\s\"])(\")([^\"]{1,})([^\s\"(])(\")/g, "$1З$3$4И");
while (/(З)([^И]*)(З)/.test(s))
s = s.replace(/(З)([^И]*)(З)([^И]*)(И)/g, "$1$2&bdquo;$4&ldquo;");
}

s = s.replace (/  +/g,' '); 

s = s.replace (/З/g,'&laquo;'); 
s = s.replace (/И/g,'&raquo;'); 
s = s.replace (/ -{1,2} /g,'&nbsp;&#151; '); 
s = s.replace (/\.{3}/g,'&hellip;'); 
s = s.replace (/([>|\s])- /g,"$1&#151; "); 
s = s.replace (/^- /g,"&#151; "); 

s = s.replace (/(\d)-(\d)/g, "$1&#150;$2");
s = s.replace (/(\S+)-(\S+)/g, "<nobr>$1-$2</nobr>");
s = s.replace (/(\d)\s/g, "$1&nbsp;");

s = s.replace (/([Ђ-џа-ЯA-Za-z]) (ли|ль|же|ж|бы|б|млн|млрд|руб)([^Ђ-џа-ЯA-Za-z])/gi, '$1&nbsp;$2$3' );
s = s.replace (/(\s)([Ђ-џа-Я]{1})\s/g, '$1$2&nbsp;' );

// инициалы 

// A.C. Џушкин
s = s.replace (/([Ђ-џA-Z])([\. ]{1})[ ]{0,1}([Ђ-џA-Z])([\. ]{1})[ ]{0,1}([Ђ-џA-Z][Ђ-џа-ЯA-Za-z]*)/g, '$1.&nbsp;$3.&nbsp;$5');

// Џушкин Ђ. ‘.
s = s.replace (/([Ђ-џA-Z][Ђ-џа-ЯA-Za-z]*) ([Ђ-џA-Z])[\. ]{1}[ ]{0,1}([Ђ-џA-Z])\.([,\ )]{1})/g, '$1&nbsp;$2.&nbsp;$3.$4');

// сокращениЯ типа ул.

s = s.replace (/(\s[а-Я]{1,2})\.\s/g, '$1.&nbsp;' );

// (Ђ.‘.Џушкин)  
 // (Џушкин Ђ.‘) 
 // (Џушкин Ђ. ‘) 

s = s.replace (/'/g, "&#146;");
s = s.replace (/\(c\)/gi, "&copy;");
s = s.replace (/\(r\)/gi, "&reg;");
s = s.replace (/\(tm\)/gi, "&trade;");
s = s.replace (/Ь /gi, "&#8470;&nbsp;");



// out array
i=0;
r=/\x01([0-9]*)\x02/;
while (r.test(s))
{
i++;
s = s.replace(r, a[i-1]);
}
}

s = s.substr (1, s.length-1);
$after=s.length;

s = s.replace(/<nobr><\/nobr>/g, "");
s = s.replace(/<nobr><nobr>/g, "<nobr>");
s = s.replace(/<\/nobr><\/nobr>/g, "</nobr>");

document.post.content.value=s;
}

</script>
<?php
}
}

function kavychker_footer() {
if (preg_match("/page[\-new]+\.php/",$_SERVER['REQUEST_URI']) || preg_match("/post[\-new]+\.php/",$_SERVER['REQUEST_URI'])) {
	?>
	<script type="text/javascript">
	
var what = document.createElement("input");
var mform = document.getElementById("post");
what.setAttribute("name", "what");
what.setAttribute("value", "1");
what.setAttribute("type", "hidden");
what.setAttribute("id", "what");
mform.appendChild(what);

document.getElementById("post").onsubmit = function () {
	//document.getElementById("edButtonHTML").click();
	var ed = tinyMCE.get('content');
	if (! ed || ed.isHidden()) {
		ht();
	} else {
		switchEditors.go('content');
		ht();
		switchEditors.go('content');
	}
};
	
	</script>
	<?php
	}
}

?>