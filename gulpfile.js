// ## Globals
var gulp = require('gulp');
var harp = require("harp");
var runSequence = require('run-sequence');
var copy2 = require('gulp-copy2');
var shell = require('gulp-shell');
var deploy = require('gulp-gh-pages');

/**
 * 复制生成的前端文件到 halp 目录
 */
gulp.task('copy', function () {
    var paths = [{src: 'front/dist/**/*', dest: 'docs/www/dist/'}];
    return copy2(paths);
});


/**
 * 编译 docs 文件夹里面的说明文档
 */
gulp.task('harp', shell.task(['harp compile docs', 'gulp copy']));


/**
 * 编译所有资源
 */
gulp.task('build', function (callback) {
    runSequence('harp', 'copy', callback);
});

/**
 * 发布到 gh-pages
 */
gulp.task('deploy', ['build'], function () {
    return gulp.src("docs/www/**/*")
        .pipe(deploy());
});