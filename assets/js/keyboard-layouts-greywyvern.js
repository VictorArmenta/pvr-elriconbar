/*! Layouts from greywyvern.com *//*
 All layouts in this file have been generated & modified from the
 Javascript Graphical / Virtual Keyboard Interface
 (http://www.greywyvern.com/code/javascript/keyboard)

 Copyright (c) 2014 - GreyWyvern
 Licenced for free distribution under the BSDL

  *** action key language translations not included ***

  Arabic keyboard layout by Srinivas Reddy
  Armenian Eastern and Western keyboard layouts by Hayastan Project (www.hayastan.co.uk)
  Assamese keyboard layout by Kanchan Gogoi
  Basic Japanese Hiragana/Katakana keyboard layout by Damjan
  Belarusian and Serbian Cyrillic keyboard layouts by Evgeniy Titov
  Bosnian/Croatian/Serbian Latin/Slovenian keyboard layout by Miran Zeljko
  Bulgarian BDS keyboard layout by Milen Georgiev
  Bulgarian Phonetic keyboard layout by Samuil Gospodinov
  Burmese keyboard layout by Cetanapa
  Danish keyboard layout by Verner Kjærsgaard
  Dari keyboard layout by Saif Fazel
  Dutch and US Int'l keyboard layouts by jerone
  Farsi (Persian) keyboard layout by Kaveh Bakhtiyari (www.bakhtiyari.com)
  French keyboard layout by Hidden Evil
  German keyboard layout by QuHno
  Hungarian keyboard layout by Antal Sall 'Hiromacu'
  Italian and Spanish (Spain) keyboard layouts by dictionarist.com
  Kazakh keyboard layout by Alex Madyankin
  Khmer keyboard layout by Sovann Heng (km-kh.com)
  Kurdish keyboard layout by Ara Qadir
  Lithuanian and Russian keyboard layouts by Ramunas
  Macedonian keyboard layout by Damjan Dimitrioski
  Pashto keyboard layout by Ahmad Wali Achakzai (qamosona.com)
  Pinyin keyboard layout from a collaboration with Lou Winklemann
  Polish Programmers layout by moose
  Romanian keyboard layout by Aurel
  Slovak keyboard layout by Daniel Lara (www.learningslovak.com)
  Swedish keyboard layout by Håkan Sandberg
  Turkish keyboard layouts by offcu
  Ukrainian keyboard layout by Dmitry Nikitin
  Urdu Phonetic keyboard layout by Khalid Malik
  Yiddish (Yidish Lebt) keyboard layout by Simche Taub (jidysz.net)
  Yiddish keyboard layout by Helmut Wollmersdorfer
*/
/*
 Zero-width characters
 U+200B ZWSP
 U+200C ZWNJ
 U+200D ZWJ
 U+200E LEFT-TO-RIGHT MARK
 U+200F RIGHT-TO-LEFT MARK
*/







/* Spanish Keyboard Layout (Espa\u00f1ol) * generated from http://www.greywyvern.com/code/javascript/keyboard layouts */
jQuery.keyboard.layouts["es"] = {
	"name":"Spanish (Espa\u00f1ol)",
	"normal":[
		"\u00ba 1 2 3 4 5 6 7 8 9 0 ' \u00a1 {b}",
		"{t} q w e r t y u i o p ` + \u00e7",
		"a s d f g h j k l \u00f1 \u00b4 {enter}",
		"{s} < z x c v b n m , . - {s}",
		"{space} {alt} {accept}"
	],
	"shift":[
		"\u00aa ! \" ' $ % & / ( ) = ? \u00bf {b}",
		"{t} Q W E R T Y U I O P ^ * \u00c7",
		"A S D F G H J K L \u00d1 \u00a8 {enter}",
		"{s} > Z X C V B N M ; : _ {s}",
		"{space} {alt} {accept}"
	],
	"alt":[
		"\\ | @ # ~ \u20ac \u00ac {empty} {empty} {empty} {empty} {empty} {empty} {b}",
		"{t} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} [ ] }",
		"{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} { {enter}",
		"{s} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {s}",
		"{space} {alt} {accept}"
	],
	"alt-shift":[
		"{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {b}",
		"{t} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty}",
		"{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {enter}",
		"{s} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {s}",
		"{space} {alt} {accept}"
	],
	"lang":["es"]
};



/* Portuguese (Brazil) Keyboard Layout (Portugu\u00eas Brasileiro) * generated from http://www.greywyvern.com/code/javascript/keyboard layouts */
/*jQuery.keyboard.layouts["pt-BR"] = {
	"name":"Portuguese (Brazil) (Portugu\u00eas Brasileiro)",
	"normal":[
		"' 1 2 3 4 5 6 7 8 9 0 - = {b}",
		"{t} q w e r t y u i o p \u00b4 [ {enter}",
		"a s d f g h j k l \u00e7 ~ ] /",
		"{s} \\ z x c v b n m , . : {s}",
		"{space} {alt} {accept}"
	],
	"shift":[
		"\" ! @ # $ % \u00a8 & * ( ) _ + {b}",
		"{t} Q W E R T Y U I O P ` { {enter}",
		"A S D F G H J K L \u00c7 ^ } ?",
		"{s} | Z X C V B N M < > : {s}",
		"{space} {alt} {accept}"
	],
	"alt":[
		"{empty} \u00b9 \u00b2 \u00b3 \u00a3 \u00a2 \u00ac {empty} {empty} {empty} {empty} {empty} \u00a7 {b}",
		"{t} / ? \u20ac {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} \u00aa {enter}",
		"{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} \u00ba {empty}",
		"{s} {empty} {empty} {empty} \u20a2 {empty} {empty} {empty} {empty} {empty} {empty} {empty} {s}",
		"{space} {alt} {accept}"
	],
	"alt-shift":[
		"{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {b}",
		"{t} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {enter}",
		"{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty}",
		"{s} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {s}",
		"{space} {alt} {accept}"
	],
	"lang":["pt-BR"]
};*/