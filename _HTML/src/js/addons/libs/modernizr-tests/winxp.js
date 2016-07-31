/*!
{
  "name": "winxp",
  "property": "winxp"
}
!*/


/**
 * Определение операционной системы **Windows XP**
 *
 * @memberof 	modernizrTests
 * @name 		winxp
 * @sourcecode 	modernizrTest:winxp
 * @newscope	test
*/
	define(['Modernizr'], function(Modernizr) {
		Modernizr.addTest(
			'winxp',
			(navigator.userAgent.toLowerCase().indexOf('windows nt 5.1') > 0)
		);
	});
// endcode modernizrTest:winxp
