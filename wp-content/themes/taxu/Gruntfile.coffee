'use strict'
path = require('path')

module.exports = (grunt) ->
  pkg = grunt.file.readJSON 'package.json'
  bowerJson = grunt.file.readJSON 'bower.json'
  sftpConfig = grunt.file.readJSON 'sftp-config.json'

  grunt.initConfig
    pkg: pkg,
    sftpConfig: sftpConfig,

    dir :
      path: '.'
      css : '<%= dir.path %>/stylesheets'
      js : '<%= dir.path %>/javascripts'
      img : '<%= dir.path %>/images'
      coffee : '<%= dir.path %>/coffee'
      sass : '<%= dir.path %>/sass'

    ###
    setup tasks
    ###
    bower:
      install:
        options:
          targetDir: 'vendor'
          layout: 'byType'
          install: true
          verbose: false
          cleanTargetDir: true
          cleanBowerDir: true
      confirm:
        options:
          targetDir: 'vendor'
          install: true
          verbose: false
          cleanTargetDir: true
          cleanBowerDir: false
          layout: 'byType'

    ###
    development tasks
    ###
    connect:
      server:
        options:
          port: 7000
          hostname: pkg.name
          # open: true

    php:
      server:
        options:
          port: 7000
          hostname: pkg.name
          open: true
          keepalive: false

    watch:
      coffee:
        files:
          '<%= dir.coffee %>/**/*.coffee'
        tasks:[
          'coffee'
          'notify:coffee'
        ]

      sass:
        files: [
          '<%= dir.sass %>/**/*.sass'
          '<%= dir.sass %>/**/*.scss'
          '<%= dir.sass %>/**/**/*.scss'
        ]
        tasks:[
          'compass:dev'
          'notify:sass'
        ]

      livereload:
        files:[
          '<%= dir.js %>/**/*.js'
          '<%= dir.path %>/**/*.html'
          '<%= dir.path %>/**/*.php'
        ]
        options:
          livereload: true
        tasks: [
          'notify:livereload'
        ]

      livereloadCss:
        files:[
          '<%= dir.css %>/**/*.css'
        ]
        options:
          livereload: true
        tasks: [
          'notify:livereload'
        ]
      kss:
        files:[
          '<%= dir.sass %>/styleguide.md'
          'styleguide/template/**/*'
        ]
        options:
          livereload: true
        tasks: [
          'kss'
          'notify:livereload'
        ]


    ###
    metalanguage tasks
    ###
    sass:
      vendor:
        files:
          'vendor/stylesheets/foundation/foundation.css' : 'vendor/sass/foundation/foundation.scss'

    compass:
      dev:
        src: '<%= dir.sass %>'
        dest: '<%= dir.css %>'
        outputstyle: 'expanded'
        linecomments: true
        sourcemap: true
        debugsass: true
        images: '<%= dir.img %>'
        # relativeassets: true

      pro:
        src: '<%= dir.sass %>'
        dest: '<%= dir.css %>/style.min.css'
        outputstyle: 'compressed'
        linecomments: false
        forcecompile: true
        require: [
        ]
        debugsass: false
        images: '<%= dir.sass %>'

    coffee:
      # glob_to_multiple:
      #   expand: true
      #   cwd: '<%= dir.coffee %>'
      #   src: ['*.coffee']
      #   dest: '<%= dir.js %>'
      #   ext: '.js'
      compile:
        files:
          '<%= dir.js %>/script.js': '<%= dir.coffee %>/**/*.coffee'
        options:
          bare: true
          join: false
          sourceMap: false
        # flatten: false


    ###
    minify tasks
    ###
    concat:
      style:
        options:
          separator: ''
        src: [
          '<%= dir.css %>/libs/foundation/normalize.css'
          '<%= dir.css %>/libs/foundation/foundation.css'
          '<%= dir.css %>/vendor.css'
          '<%= dir.css %>/style.css'
        ]
        dest: '<%= dir.css %>/style.min.css'

      vendorScripts:
        options:
          separator: ';'
        src: [
          'vendor/javascripts/jquery/*.js'
          'vendor/javascripts/**/jquery*.js'
          'vendor/javascripts/**/*.js'
        ]
        dest: '<%= dir.js %>/vendor.js'
      vendorStyles:
        options:
          separator: ''
        src: [
          'vendor/stylesheets/**/*.css'
        ]
        dest: '<%= dir.css %>/vendor.css'


    uglify:
      script:
        files:
          '<%= dir.js %>/script.min.js': [
            '<%= dir.js %>/vendor.js'
            '<%= dir.js %>/script.js'
          ]
      vendor:
        files:
          '<%= dir.js %>/vendor.js': [
            '<%= dir.js %>/vendor.js'
          ]
    removelogging:
      dist:
        src: "<%= dir.js %>/script.js"
        dest: "<%= dir.js %>/script.js"

    csso:
      compress:
        options:
          banner: '/* Copyright nD inc. */'
          restructure: true
          # report: 'false'

        files:
          '<%= dir.css %>/style.min.css': [
            '<%= dir.css %>/libs/foundation/normalize.css'
            '<%= dir.css %>/libs/foundation/foundation.css'
            '<%= dir.css %>/vendor.css'
            '<%= dir.css %>/style.css'
          ]

    # combine-media-queries
    cmq:
      options:
        log: true
      your_target:
        files:
          '<%= dir.css %>': [
            '<%= dir.css %>/vendor.css'
            '<%= dir.css %>/style.css'
          ]

    autoprefixer:
      pro:
        src: '<%= dir.css %>/style.min.css'


    imageoptim:
      pro:
        options:
          jpegMini: true
          imageAlpha: false
          quitAfter: true
        src: [
          '<%= dir.img %>'
        ]

    copy:
      foundation_settings:
        src: '<%= dir.sass %>/libs/foundation/foundation/_settings.scss'
        dest: 'vendor/sass/foundation/foundation'
        expand: true
        flatten: true

      foundation_base:
        src: '<%= dir.sass %>/libs/foundation/foundation.scss'
        dest: 'vendor/sass/foundation/foundation.scss'

      foundation_all:
        expand: true
        cwd: 'vendor/sass/foundation/'
        src: '**/*'
        dest: '<%= dir.sass %>/libs/foundation/'

      ie:
        expand: true
        cwd: 'vendor/ie/'
        src: '**/*'
        dest: '<%= dir.js %>/ie/'

      svg:
        expand: true
        cwd: 'vendor/svg/'
        src: '**/*'
        dest: './svg/'

    manifest:
      generate:
        options:
          basePath: '<%= dir.path %>'
          cache: do ->
            cache = [

            ]

          network: [
            '*'
            '<%= dir.img %>/sprite_base/'
          ]
          # fallback: ['/ /offline.html']
          # exclude: ['<%= dir.img %>/respond_guide.png']
          preferOnline: false
          verbose: false
          timestamp: true
        src: [
          '<%= dir.js %>/script.min.js'
          '<%= dir.js %>/vendor.js'
          '<%= dir.css %>/style.min.css'
          '<%= dir.img %>/*.png'
          '<%= dir.img %>/*.jpg'
          '<%= dir.img %>/*.gif'
        ]
        dest: 'manifest.appcache'

    ###
    other tasks
    ###
    notify:
      sass:
        options:
          title: 'Sass Task Complete'
          message: 'Sass finished running'
      coffee:
        options:
          title: 'Coffee Task Complete'
          message: 'coffee finished running'
      livereload:
        options:
          title: 'livereload Task Complete'
          message: 'livereload finished running'
      min:
        options:
          title: 'min Task Complete'
          message: 'min task finished running'

      deploy:
        options:
          title: 'deploy Task Complete'
          message: 'deploy task finished running'

    kss:
      options:
        includeType: 'css'
        includePath: '<%= dir.css %>/style.css'
        template: 'styleguide/template'

      dist:
        files:
          'styleguide/docs/': [
            '<%= dir.sass %>'
          ]

    # https://console.developers.google.com/project?utm_referrer=http:%2F%2Fqiita.com%2Fshoito%2Fitems%2Faed36f56ae9a3e46c215
    pagespeed:
      options:
        nokey: true
        url: 'http://<%= pkg.name %>.ngrok.com'
      desktop:
        options:
          locale: "ja_JP"
          strategy: "desktop"
          threshold: 50
          paths: [
            "/"
          ]
      mobile:
        options:
          locale: "ja_JP"
          strategy: "mobile"
          threshold: 50
          paths: [
            "/"
          ]
    open:
      ngrok:
        path: 'http://<%= pkg.name %>.ngrok.com'
        app: 'Google Chrome'
        options:
          delay: 400
      dev:
        path: 'http://local.<%= pkg.name %>.com'
        app: 'Google Chrome'
      staging:
        path: '<%= sftpConfig.domain %>'
        app: 'Google Chrome'
      production:
        path: '<%= sftpConfig.production.domain %>'
        app: 'Google Chrome'


    release:
      options:
        tagName: 'v<%= version %>'
        commitMessage: 'check out my release <%= version %>'
        commit: true
        npm: false
        push: false
        pushTags: false
        tag: false

    shell:
      options:
        stdout: true
        stderr: true
      'push-staging-db':
        command: do ->
          cmd = [
            'mysqldump -u <%= sftpConfig.db.local.user %> -p<%= sftpConfig.db.local.password %> <%= sftpConfig.db.local.name %>'
            ' | '
            'ssh <%= sftpConfig.user %>@<%= sftpConfig.host %> -p <%= sftpConfig.port %> '
            '\"mysql -h <%= sftpConfig.db.staging.host %> -u <%= sftpConfig.db.staging.user %> -p<%= sftpConfig.db.staging.password %>  <%= sftpConfig.db.staging.name %>\"'
          ]
          return cmd.join ' '

      'pull-staging-db':
        command: do ->
          cmd = [
            'ssh <%= sftpConfig.user %>@<%= sftpConfig.host %> -p <%= sftpConfig.port %> '
            '\"mysqldump -h <%= sftpConfig.db.staging.host %> -u <%= sftpConfig.db.staging.user %> -p<%= sftpConfig.db.staging.password %>  <%= sftpConfig.db.staging.name %>\"'
            ' | '
            'mysql -u <%= sftpConfig.db.local.user %> -p<%= sftpConfig.db.local.password %> <%= sftpConfig.db.local.name %>'
          ]
          return cmd.join ' '
      'push-production-db':
        command: do ->
          cmd = [
            'mysqldump -u <%= sftpConfig.db.local.user %> -p<%= sftpConfig.db.local.password %> <%= sftpConfig.db.local.name %>'
            ' | '
            'ssh <%= sftpConfig.production.user %>@<%= sftpConfig.production.host %> -p <%= sftpConfig.production.port %> '
            '\"mysql -h <%= sftpConfig.db.production.host %> -u <%= sftpConfig.db.production.user %> -p<%= sftpConfig.db.production.password %>  <%= sftpConfig.db.production.name %>\"'
          ]
          return cmd.join ' '

      'pull-production-db':
        command: do ->
          cmd = [
            'ssh <%= sftpConfig.production.user %>@<%= sftpConfig.production.host %> -p <%= sftpConfig.production.port %> '
            '\"mysqldump -h <%= sftpConfig.db.production.host %> -u <%= sftpConfig.db.production.user %> -p<%= sftpConfig.db.production.password %>  <%= sftpConfig.db.production.name %>\"'
            ' | '
            'mysql -u <%= sftpConfig.db.local.user %> -p<%= sftpConfig.db.local.password %> <%= sftpConfig.db.local.name %>'
          ]
          return cmd.join ' '

      'deploy-staging':
        command: do ->
          cmd = [
            'lftp'
            '-u <%= sftpConfig.user %>,<%= sftpConfig.password %>'
            '-p <%= sftpConfig.port %>'
            '<%= sftpConfig.type %>://<%= sftpConfig.host %>'
            '-e "mirror -Rev'
            '-X ' + sftpConfig.exclude_glob.join(' -X ')
            '.'
            '<%= sftpConfig.remote_path %>;' #remote path
            'bye;"'
          ]
          return cmd.join ' '
      'deploy-staging-wp':
        command: do ->
          cmd = [
            'lftp'
            '-u <%= sftpConfig.user %>,<%= sftpConfig.password %>'
            '-p <%= sftpConfig.port %>'
            '<%= sftpConfig.type %>://<%= sftpConfig.host %>'
            '-e "mirror -Rev'
            '-X ' + sftpConfig.exclude_glob.join(' -X ')
            './../../../../'
            sftpConfig.remote_path.replace('\/wp\/wp-content\/themes\/'+pkg.name,'') + ';' #remote path
            'bye;"'
          ]
          return cmd.join ' '
      'deploy-pro':
        command: do ->
          cmd = [
            'lftp'
            '-u <%= sftpConfig.production.user %>,<%= sftpConfig.production.password %>'
            '-p <%= sftpConfig.production.port %>'
            '<%= sftpConfig.type %>://<%= sftpConfig.production.host %>'
            '-e "mirror -Rev'
            '-X ' + sftpConfig.exclude_glob.join(' -X ')
            '.'
            '<%= sftpConfig.production.remote_path %>;' #remote path
            'bye;"'
          ]
          return cmd.join ' '
      'deploy-pro-wp':
        command: do ->
          cmd = [
            'lftp'
            '-u <%= sftpConfig.production.user %>,<%= sftpConfig.production.password %>'
            '-p <%= sftpConfig.production.port %>'
            '<%= sftpConfig.type %>://<%= sftpConfig.production.host %>'
            '-e "mirror -Rev'
            '-X ' + sftpConfig.exclude_glob.join(' -X ')
            './../../../../'
            sftpConfig.production.remote_path.replace('\/wp\/wp-content\/themes\/'+pkg.name,'') + ';' #remote path
            'bye;"'
          ]
          return cmd.join ' '


      'flow-release':
        command: [
          'git flow release start <%= pkg.version %>',
          'git flow release finish -pm "release:<%= pkg.version %>" <%= pkg.version %>',
          'git checkout develop'
        ].join('&&')

      'hot-start':
        command: [
          'git flow hotfix start <%= pkg.version %>',
        ].join('&&')

      'hot-finish':
        command: [
          'git flow hotfix finish -pm "hotfix:<%= pkg.version %>" <%= pkg.version %>',
          'git checkout develop'
        ].join('&&')

      'flow-start':
        command: [
          'git branch'
          'git flow feature start <%= pkg.version %>'
        ].join('&&')

      'flow-finish':
        command: [
          'git add .'
          'git commit -am "update <%= pkg.version %>"'
          'git branch'
          'git flow feature finish <%= pkg.version %>'
          'git pull origin develop'
          'git mergetool -y'
          'git push origin develop'
          'git branch'
        ].join('&&')
      'commit':
        command:[
          'git add .'
          'git commit -am "update <%= pkg.version %>"'
        ]


  for taskName of pkg.devDependencies
    grunt.loadNpmTasks taskName if taskName.substring(0,6) == 'grunt-'

  ngrok = require('ngrok')

  ## function
  grunt.registerTask 'ngrok', 'Run ngrok', () ->
    done = @async()

    ngrok.connect
      authtoken: 'rXs9U5lI6eVX5OhtsO4J'
      subdomain: pkg.name
      port: 80
    , (err, https_url) ->
      url = https_url.replace('https', 'http');
      # console.log url
      if err != null
        grunt.fail.fatal(err)
        return done()

    done()

  grunt.registerTask 'rewrite-json', (key, value)=>
    pkg[key] = value
    grunt.file.write 'package.json', JSON.stringify(pkg, null, '\t')

  grunt.registerTask 'rewrite-bowerjson', (key, value)=>
    bowerJson[key] = value
    grunt.file.write 'bower.json', JSON.stringify(bowerJson, null, '\t')

  ## process

  grunt.registerTask 'default',[
    'server:dev'
  ]

  grunt.registerTask 'n',[
    'server:ngrok'
  ]

  grunt.registerTask 'server:ngrok',[
    'ngrok'
    'open:ngrok'
    'watch'
  ]
  grunt.registerTask 'server:dev',[
    'open:dev'
    'watch'
  ]

  grunt.registerTask 'install',[
    'bower:install'
    'copy:foundation_base'
    'copy:foundation_settings'
    'copy:foundation_all'
    'copy:ie'
    'copy:svg'
    # 'sass:vendor'
    'concat:vendorScripts'
    'concat:vendorStyles'
  ]

  grunt.registerTask 'min',[
    'minify'
  ]

  grunt.registerTask 'minify',[
    'coffee'
    'removelogging'
    'uglify'
    'compass:pro'
    'cmq'
    'csso'
    'autoprefixer'
    'notify:min'
  ]

  grunt.registerTask 'staging:all',[
    'imageoptim:pro'
    'staging'
    'pagespeed'
  ]

  grunt.registerTask 'staging',[
    'minify'
    'shell:deploy-staging'
    'release:patch'
    'notify:deploy'
  ]


  grunt.registerTask 'publish',[
    # 'imageoptim:pro'
    'minify'
    # 'shell:flow-release'
    'shell:deploy-pro'
    # 'release:minor' #or major
    'notify:deploy'
  ]

  grunt.registerTask 'guide',[
    'compass'
    'kss'
  ]

###
  memo grunt task
  "grunt-webfont ": ">=0.6.0",
###
