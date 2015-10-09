var gulp    = require('gulp'),
    notify  = require('gulp-notify'),
    phpspec = require('gulp-phpspec'),
    yargs   = require('yargs');

var namespace = 'CubicMushroom\\Hexagonal\\';

//console.dir(yargs);

yargs.options({
  'c': {
    alias   : 'class',
    describe: 'Class to describe',
    type    : 'string', /* array | boolean | string */
    nargs   : 1
  }
});
if (!yargs.argv.h) {
  yargs.demand('c', 'Class (-c, --class) is required');
}

var argv = yargs.argv;


// -----------------------------------------------------------------------------------------------------------------
// PHPSpec Tasks
// -----------------------------------------------------------------------------------------------------------------

var phpspecGlob = 'spec/**/*Spec.php';
var srcGlob = 'src/**/*.php';

gulp.task('desc', function () {
  if (argv.h || !argv.c) {
    yargs.showHelp();
    return;
  }


});

// phpspec
gulp.task('phpspec', function () {
  var options = {debug: true, notify: true};

  gulp.src(phpspecGlob)
    .pipe(phpspec('', options))
    .on('error', notify.onError({
      title  : "Testing Failed",
      message: "Error(s) occurred during PHPSpec test..."
    }));
});

// watch
gulp.task('watch', function () {
  gulp.watch([phpspecGlob, srcGlob], ['phpspec']);
});