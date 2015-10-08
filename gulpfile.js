var gulp    = require('gulp'),
    notify  = require('gulp-notify'),
    phpspec = require('gulp-phpspec');

var phpspecGlob = 'spec/**/*Spec.php';
var srcGlob = 'src/**/*.php';

gulp.task('phpspec', function () {
  var options = {debug: true, notify: true};

  gulp.src(phpspecGlob)
    .pipe(phpspec('', options))
    .on('error', notify.onError({
      title  : "Testing Failed",
      message: "Error(s) occurred during PHPSpec test..."
    }));
});

gulp.task('watch', function () {
  gulp.watch([phpspecGlob, srcGlob], ['phpspec']);
});