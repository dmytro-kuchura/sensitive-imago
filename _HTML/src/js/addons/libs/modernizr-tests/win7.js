/*!
{
  "name": "win7",
  "property": "win7"
}
!*/


/**
 * Определение операционной системы **Windows 7**
 *
 * @memberof 	modernizrTests
 * @name 		win7
 * @sourcecode 	modernizrTest:win7
 * @newscope	test
*/
	define(['Modernizr'], function(Modernizr) {
		Modernizr.addTest(
			'win7',
			(navigator.userAgent.toLowerCase().indexOf('windows nt 6.1') > 0)
		);
	});
// endcode modernizrTest:win7
