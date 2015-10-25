gulp = require 'gulp'
portfinder = require 'portfinder'
connect = require 'gulp-connect-php'
browserSync = require('browser-sync').create()

# Load envoriment
require('dotenv').load()

# Init local development server
gulp.task 'default', ->
	# Try to find an open port
	portfinder.getPort (error, port)->
		# Start PHP backend
		connect.server ( host: 'localhost', port: port ), ->
			# Init Browsersync
			browserSync.init
				files: '<%= paths.app() %>/{*,**/}*.{php|js|css|jpg|gif|png}'
				proxy:
					target: "localhost:#{port}",
					reqHeaders: (config)->
						host: 'localhost:3000'
				snippetOptions:
					ignorePaths: 'wordpress/**'
					fn: (snippet, match)-> snippet + match
