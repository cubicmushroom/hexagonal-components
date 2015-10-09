var gulp         = require('gulp'),
    phpspecTasks = require('./gulp/phpspec-tasks');

phpspecTasks.addTasks(gulp, 'CubicMushroom\\Hexagonal\\');