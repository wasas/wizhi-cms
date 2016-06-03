// ## Globals
var gulp = require('gulp');
var deploy = require('gulp-gh-pages');

/**
 * 发布到 gh-pages
 */
gulp.task('deploy', function () {
    return gulp.src("docs/**/*")
        .pipe(deploy());
});