var gulp         = require('gulp'),
    phpspecTasks = require('gulp-cm-phpspec-tasks');

var phpspecNamespace = 'CubicMushroom\\Hexagonal\\';

phpspecTasks.addTasks(gulp, phpspecNamespace);