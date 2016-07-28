'use strict';

/**
 * @module 		tasks/transfer
 * @sourcefile 	file:tasks:transfer
*/





// подключение nodejs модулей
// ==========================
	import gulp from 'gulp';
	import multipipe from 'multipipe';
	import gulpLoadPlugins from 'gulp-load-plugins';
	const $ = gulpLoadPlugins();

// подключение внутренних модулей
// ==============================
	import _modulesParams from './_modules-params.js';





/**
 * Модуль трансфера. Используеться для переброса файлов без обработки.
 *
 * ### Оболочка задачи
 *
 * *Пример*
 * ```javascript
 * lazyRequireTask('sass:statics', `${tasks}/transfer`, {
 * 	src: _sassStatics,
 * 	dest: _sassDest,
 * 	filter: `combine`,
 * 	watch: [
 * 		_sassStatics
 * 	],
 * 	notify: true
 * });
 * ```
 *
 * ### Настройка задачи
 * Одним из основных параметров управления - являеться фильтровка файлов - `filter` :
 * - `false` - отключения фильтровки
 * - `'since'` - фильтр на уровне получения источников, который будет воспринимать только изминенные файлы после последнего выполнения текущей задачи. Задача будет полезна при использовании *watch'ей*, при одиночном вызове - никакого эфекта не будет, также следует помнить что при первом выполнении задача бужет перекидывать все файлы, так как предыдущего вызова еще не было. Но данный метод быстрее чем **newer** ~40-60%.
 * - `'newer'` - фильтр который получает все источники и сравнивает с итоговыми файлами (по дате) - перебрасывает только новые. При подобной фильтровке даже при первом запуске будут отфльтрованны только новые файлы. Также, в случае если удалить итоговые файлы и выполнить задачу, данный метод перебросит и новые файлы на место удаленных, метод **since** этого не сделает, так как он не смотрит на итоговые файлы. Но **newer** работает дольше почти на половину.
 * - `'combine'` - фильтр который объеденяет методы *newer* и *since*. При первом запуске будет выполнена проверка новых файлов в сравнении с итоговыми на основе *newer*, далее при последующих вызовах фильтровка будет на основе метода *since*. **Этот метод используется по умолчанию**
 *
 * > *На заметку*
 * > Чем больше входящих файлов - тем дольше идет фильтровка!
 *
 * Пример сравнения переброса одного и того же файла при вотче и ***количестве входящих файлов - 36 шт.:***
 *
 * since | newer | false (переброс всех файлов)
 * --- | --- | ---
 * ~ 121-154 ms | ~ 246-268 ms | ~286-360 ms
 *
 * Пример сравнения переброса одного и того же файла при вотче и ***количестве входящих файлов - 336 шт.:***
 *
 * since | newer | false (переброс всех файлов)
 * --- | --- | ---
 * ~ 240-544 ms | ~ 849 ms - 1.2 s | ~ 1.5-1.86 s
 *
 *
 * ### Плагины imagemin
 *
 * - {@link https://github.com/imagemin/imagemin-gifsicle}
 * - {@link https://github.com/imagemin/imagemin-jpegtran}
 * - {@link https://github.com/imagemin/imagemin-optipng}
 * - {@link https://github.com/imagemin/imagemin-svgo}
 *
 *
 * @todo 		для минификации изображений, нужно чтение буффера. Определиться, стоит ли вынести оптимизацию изображений в отдельную задачу
 * @todo 		Настроить и описать принцип минификации изображений
 *
 * @moduleLocal
 * @sourcecode	code:tasks:transfer
 *
 * @requires   	{@link https://github.com/gulpjs/gulp/tree/4.0|gulpjs/gulp#4.0}
 * @requires   	{@link https://www.npmjs.com/package/gulp-load-plugins}
 * @requires   	{@link https://www.npmjs.com/package/gulp-if}
 * @requires   	{@link https://www.npmjs.com/package/gulp-newer}
 * @requires   	{@link https://www.npmjs.com/package/gulp-notify}
 * @requires   	{@link https://www.npmjs.com/package/gulp-imagemin}
 * @requires   	module:tasks/_modules-params
 *
 * @param		{Object}		options - передаваемые параметры
 * @param		{string}		options.taskName - имя вызывающей задачи, *задаеться автоматически*
 * @param		{boolean}		options.isDevelop - флаг dev версии сборки, *задаеться автоматически*
 * @param		{boolean}		options.isProduction - флаг production версии сборки, *задаеться автоматически*
 * @param		{Object}		options.package - данные из `package.json`, *задаеться автоматически*
 * @param		{string}		options.dest - путь к итоговой директории
 * @param		{string}		options.src - путь к исходной директории
 * @param		{boolean} 		[options.imagemin] - флаг миницикации изображений
 * @param		{string|boolean} [options.filter='combine'] - метод фильтровки файлов
 * @param		{boolean}		[options.notify=false] - выводить уведомление по окончанию трансфера
 * @param		{string}		[options.notifyOn='last'] - метод уведомления, параметр передается дальше методу {@link module:tasks/_modules-params~modulesParams#gulpNotify|modulesParams#gulpNotify}
 * @param		{number}		[options.notifyTime=2000] - время показа уведомления, параметр передается дальше методу {@link module:tasks/_modules-params~modulesParams#gulpNotify|modulesParams#gulpNotify}
 * @param		{boolean}		[options.notifyIsShort=false] - выводить краткое уведомдение - только количество файлов, параметр воздействует только при опции `notifyOn:'last'`. Параметр передается дальше методу {@link module:tasks/_modules-params~modulesParams#gulpNotify|modulesParams#gulpNotify}
 *
 * @return		{Function}
*/
module.exports = function(options) {

	// возврашаем функцию для задачи
	return function(cb) {

		// vars
		// ========

			// список переброшенных файлов
			let receivedFilesList = [];

			let isImageMin = options.imagemin === true;

			// использовать newer метод ?
			let isNewer = false;

			// использовать since метод ?
			let isSince = false;

			switch(options.filter) {
				case false:
					break;
				case 'since':
					isSince = true;
					break;
				case 'newer':
					isNewer = true;
					break;
				default:
					if (options._isCombinedMethod) {
						isSince = true;
						isNewer = false;
					} else {
						isSince = false;
						isNewer = true;
						options._isCombinedMethod = true;
					}
			};





		// task
		// ========

			return gulp.src(options.src, {
					buffer: isImageMin,
					since: isSince ? gulp.lastRun(options.taskName) : 0
				})
				.pipe($.if(
					isNewer,
					$.newer(options.dest).on('error', $.notify.onError(
						_modulesParams.gulpNotifyOnError(`transfer - ${options.taskName}`))
					)
				))
				.pipe($.if(
					isImageMin,
					$.imagemin([
						$.imagemin.gifsicle(),
						$.imagemin.jpegtran(),
						$.imagemin.optipng(),
						$.imagemin.svgo()
					], {
						verbose: true
					})
					.on('error', $.notify.onError(
						_modulesParams.gulpNotifyOnError(`imagemin - ${options.taskName}`))
					)
				))
				.pipe(gulp.dest(options.dest))
				.on('data', (file) => {
					receivedFilesList.push(file.relative);
				})
				.pipe($.if(
					options.notify,
					$.notify(_modulesParams.gulpNotify(options, receivedFilesList, 'transfered'))
				));
	};
};
