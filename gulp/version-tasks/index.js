var gulp         = require('gulp'),
    git          = require('gulp-git'),
    bump         = require('gulp-bump'),
    filter       = require('gulp-filter'),
    tagVersion   = require('gulp-tag-version');

/**
 * Bumping version number and tagging the repository with it.
 * Please read http://semver.org/
 *
 * You can use the commands
 *
 *     gulp patch     # makes v0.1.0 → v0.1.1
 *     gulp feature   # makes v0.1.1 → v0.2.0
 *     gulp release   # makes v0.2.1 → v1.0.0
 *
 * To bump the version numbers accordingly after you did a patch,
 * introduced a feature or made a backwards-incompatible release.
 */

var packageFiles = ['./package.json', './composer.json'];

/**
 * Increments the version number in each of the packageFiles, commits the change & git tag's it
 *
 * @param {'major'|'minor'|'patch'|'prerelease'} importance
 */
function inc(importance) {
  // get all the files to bump version in
  return gulp.src(packageFiles)
    // bump the version number in those files
    .pipe(bump({type: importance}))
    // save it back to filesystem
    .pipe(gulp.dest('./'))
    // commit the changed version number
    .pipe(git.commit('Bumped package version'))

    // read only one file to get the version number
    .pipe(filter('composer.json'))
    // **tag it in the repository**
    .pipe(tagVersion());
}

module.exports.addTasks = function () {
  gulp.task('release:patch', function () {
    return inc('patch');
  });
  gulp.task('release:minor', function () {
    return inc('minor');
  });
  gulp.task('release:major', function () {
    return inc('major');
  });
};