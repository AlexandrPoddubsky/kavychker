<?php

/*
Plugin Name: Kavychker
Plugin URI: http://mbyte.org.ua/
Description: Replace plain text symbols with html entities (uses modified <a href="http://textus.ru">Kavychker&copy;</a> code) (wp2.5)
Version: 0.2
Author: mByte
Author URI: http://mbzeus.net/
*/

/*  Copyright 2007  mByte  (email : mbyte@mbzeus.net)

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
s = s.replace(/«/g, "\"");
s = s.replace(/»/g, "\"");
s = s.replace(/“/g, "\"");
s = s.replace(/”/g, "\"");
s = s.replace(/„/g, "\"");
s = s.replace(/…/g, "...");
s = s.replace(/–/g, "-");
s = s.replace(/—/g, "-");

// kavychking
s = s.replace(/([\x01-(\s\"])(\")([^\"]{1,})([^\s\"(])(\")/g, "$1«$3$4»");


// kavychking in kavychking
if (/"/.test(s))
{
s = s.replace(/([\x01(\s\"])(\")([^\"]{1,})([^\s\"(])(\")/g, "$1«$3$4»");
while (/(«)([^»]*)(«)/.test(s))
s = s.replace(/(«)([^»]*)(«)([^»]*)(»)/g, "$1$2&bdquo;$4&ldquo;");
}

s = s.replace (/  +/g,' '); 

s = s.replace (/«/g,'&laquo;'); 
s = s.replace (/»/g,'&raquo;'); 
s = s.replace (/ -{1,2} /g,'&nbsp;&#151; '); 
s = s.replace (/\.{3}/g,'&hellip;'); 
s = s.replace (/([>|\s])- /g,"$1&#151; "); 
s = s.replace (/^- /g,"&#151; "); 

s = s.replace (/(\d)-(\d)/g, "$1&#150;$2");
s = s.replace (/(\S+)-(\S+)/g, "<nobr>$1-$2</nobr>");
s = s.replace (/(\d)\s/g, "$1&nbsp;");

s = s.replace (/([А-Яа-яA-Za-z]) (ли|ль|же|ж|бы|б|млн|млрд|руб)([^А-Яа-яA-Za-z])/gi, '$1&nbsp;$2$3' );
s = s.replace (/(\s)([А-Яа-я]{1})\s/g, '$1$2&nbsp;' );

// инициалы 

// A.C. Пушкин
s = s.replace (/([А-ЯA-Z])([\. ]{1})[ ]{0,1}([А-ЯA-Z])([\. ]{1})[ ]{0,1}([А-ЯA-Z][А-Яа-яA-Za-z]*)/g, '$1.&nbsp;$3.&nbsp;$5');

// Пушкин А. С.
s = s.replace (/([А-ЯA-Z][А-Яа-яA-Za-z]*) ([А-ЯA-Z])[\. ]{1}[ ]{0,1}([А-ЯA-Z])\.([,\ )]{1})/g, '$1&nbsp;$2.&nbsp;$3.$4');

// сокращения типа ул.

s = s.replace (/(\s[а-я]{1,2})\.\s/g, '$1.&nbsp;' );

// (А.С.Пушкин)  
 // (Пушкин А.С) 
 // (Пушкин А. С) 

s = s.replace (/'/g, "&#146;");
s = s.replace (/\(c\)/gi, "&copy;");
s = s.replace (/\(r\)/gi, "&reg;");
s = s.replace (/\(tm\)/gi, "&trade;");
s = s.replace (/№ /gi, "&#8470;&nbsp;");



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
// alert ("Все сделано, ага...\nБыло байт: "+$before+"\nСтало байт: "+$after+"\nИтого добавлено: "+($after-$before));
}

function fpreview ()
{
var Hnd = window.open ('about:blank', null, 'width=700,height=300');
Hnd.document.open ();
Hnd.document.write('<html><head><title>Вот так это будет</title><style type=text/css> body {scrollbar-base-color: #000066; scrollbar-arrow-color: #FFFF00; scrollbar-highlight-color: #FFFFFF; scrollbar-shadow-color: #FFFFFF; scrollbar-face-color: #000000; scrollbar-track-color: #f0f0f0; } body, td {font-family: Georgia, Verdana, Arial; font-size: 12px;} A:link {text-decoration: none; color: #ff8000; text-decoration: underline;} A:visited {text-decoration: none; color: #660099; text-decoration: underline;} A:hover {text-decoration: none; color:#ff0000; text-decoration: underline;}</style></head><body marginwidth=20 marginheight=20 '+
'leftmargin=20 rightmargin=40 topmargin=20 bottommargin=20 scroll=auto bgcolor=f0f0f0 color-white><p>[ <a href=\'javascript:window.close ();\'>закрыть окно</a> ]<p>'+document.post.content.value+'<p>[ <a href=\'javascript:window.close ("mailDoneWin");\'>закрыть окно</a> ]</body></html>');
Hnd.document.close();
}

function fpaste ()
{
document.fo.m.value="";
document.fo.m.focus();
document.execCommand("paste");
document.post.content.value=document.fo.m.value;
}

function fcopy ()
{
document.fo.m.value=document.post.content.value;
document.fo.m.focus();
document.execCommand("selectall");
document.execCommand("copy");
}

function fundo ()
{
document.post.content.focus();
document.execCommand("undo");
}

function fredo ()
{
document.post.content.focus();
document.execCommand("redo");
}

function freplace ()
{
var char = showModalDialog("charmap.htm", "", "dialogWidth:200px;dialogHeight:100px;help:0;status:no;");

s = document.post.content.value;
document.post.content.focus();
sel = document.selection.createRange();
rto = window.prompt ("Replace: "+sel.text+"\nwith:");
rfrom = new RegExp (sel.text, "g");
s = s.replace (rfrom, rto);
document.post.content.value=s;

}

window.onLoad = function () {

};

</script>
<?php

}

function kavychker_footer() {
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

?>