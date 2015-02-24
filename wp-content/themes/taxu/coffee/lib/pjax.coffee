class Pjax
  constructor: (options)->
    @options =
      'el': '.js-pjax_container'
      'cls': 'js-pjax_container'
      'anchor': 'a'
      'beforeFn': ->
      'afterFn': ->
      'loadScript': true
      'loadLink': true
      'scrollSpeed': 500
      'bgimageAttr': '[data-bgimage]'
    $.extend(@options, options)

    @mainEl = @options.el
    @$mainEl = $(@mainEl)
    @moving = false
    @minLoadTime = if @options.scrollSpeed > 500 then @options.scrollSpeed + 200 else 700
    @$title = $('title')
    @$body = $('body')
    @init true if 'pushState' of window.history

  init: ()->
    @$mainEl.addClass @options.cls
    @initAnchor()
    @setPopStatsEvent()
    @loadLink 'init'

  initAnchor: ()->
    @setAnchor()
    @setClickEvent()

  setAnchor: ()->
    anchors = []
    $anchor = $(@options.anchor)
    $anchor.each (i, el) =>
      url = $(el).attr("href")
      if @isSelfDomain(url) and url.match(/jpg|jpeg|png|gif/g) == null and url.indexOf('javascript:') == -1 and url != '#' and url.indexOf('mailto:') == -1
        anchors.push el
    @$anchors = $(anchors)

  isSelfDomain: (url)->
    return (url and ( url.indexOf(window.location.host) != -1 or url.indexOf('http') == -1))

  setClickEvent: (callback)->
    @nextFn = if callback then callback else @defaultCallback
    @$anchors.off "click"
    @$anchors.on "click", (e)=>
      if @moving is false
        href = $(e.currentTarget).attr 'href'
        unless href == window.location.href
          @moving = true
          @nextFn href, false
      return false

  setPopStatsEvent: (callback)=>
    @prevFn = if callback then callback else @defaultCallback
    $(window).on 'popstate', (e)=>
      url = window.location.href
      if @isSelfDomain url
        @prevFn url, true
        return false

  loadLink: ($dom)->
    return false unless $dom
    init = if $dom == 'init' then true else false
    $dom = if init then $('html') else $dom
    @stylesheets = if @stylesheets then @stylesheets else []
    $dom.find('[rel=stylesheet]').each (i, el)=>
      $el = $(el)
      href = $el.attr 'href'
      if $.inArray(href, @stylesheets) == -1
        @stylesheets.push href
        @$body.append '<link rel="stylesheet" href="'+href+'" type="text/css">' unless init

  loadScript: ($dom)->
    return false unless $dom
    $('*').off()
    $(window).off()
    $dom.find('script').each (i, el)=>
      $el = $(el)
      src = $el.attr 'src'
      if src
        @$body.append '<script type="text/javascript" src="'+src+'"></script>'
      if $el.text()
        @$body.append '<script type="text/javascript">'+$el.text()+'</script>'


  loadContent: (url, isPopStatsEvent, callback) ->
    loadAjax = (url)->
      dfd = $.Deferred()
      $.ajax
        url: url
        dataType: "html"
        beforeSend: (xhr) ->
          xhr.setRequestHeader "X-PJAX", "true"
      .then (data)->
        dfd.resolve(data)
      ,(e)->
        console.log e
        dfd.reject()

    minTimeout = =>
      dfd = $.Deferred()
      setTimeout ->
        dfd.resolve()
      , @minLoadTime
      return dfd.promise()

    dfd = $.Deferred() unless callback

    $.when(
      loadAjax(url),
      minTimeout()
    ).done (data)=>
      parser = new DOMParser()
      dom = parser.parseFromString(data, "text/html")
      $dom = $(dom)
      title = $dom.find("title").text()
      bodyClass = $dom.find("body").attr('class')
      mainHtmls = {}
      $dom.find(@mainEl).each (i, el)->
        key = if $(el).attr 'data-pjax' then $(el).attr 'data-pjax' else 'pjax-default-'+i
        mainHtmls[key] = $(el).html()
      unless isPopStatsEvent
        window.history.pushState null, title, url
        ga 'send','pageview', '/?pjax='+url if ga
      @$title.html title
      @$body.attr 'class', bodyClass
      @loadLink($dom) if @options.loadLink
      @loadScript($dom) if @options.loadScript
      @moving = false
      if callback
        callback mainHtmls
      else
        dfd.resolve mainHtmls

    .fail ()=>
      @moving = false
      dfd.reject() unless callback

    return dfd.promise() unless callback

  defaultCallback: (url, isPopStatsEvent)->
    if url.slice('#')[0] == window.location.href and !isPopStatsEvent
      @moving = false
      return false

    @defaultBeforeFn()
    @options.beforeFn()
    @loadContent url, isPopStatsEvent
    .done (htmls) =>
      if htmls
        @$mainEl.each (i, el)->
          key = if $(el).attr 'data-pjax' then $(el).attr 'data-pjax' else 'pjax-default-'+i
          $(el).html htmls[key]

        $(@options.bgimageAttr).each (i, el)=>
          src = $(el).css('background-image').replace(/"|'/g, '').replace(/url\(|\)$/ig, '')
          @$mainEl.prepend $('<img>').attr('src', src).hide()

        @$mainEl.imagesLoaded()
        .always (instance) =>
          @defaultAfterFn()
          @options.afterFn()
          return
        .done (instance) ->
          return
        .fail ->
          return
        .progress (instance, image) ->
          # console.log image.img.src
          return
      else
        @options.afterFn()
      return
    .fail () ->
      window.location.href = url

  defaultBeforeFn: ()->
    @$body.addClass 'is-loading'
    if @options.scrollSpeed and @options.scrollSpeed > 0
      $('html,body').animate
        scrollTop: 0
      , @options.scrollSpeed, ->
        $(window).scrollTop 0

    else
      setTimeout ->
        $(window).scrollTop 0
      ,@options.scrollSpeed

  defaultAfterFn: ()->
    @$body.removeClass 'is-loading'
    twttr.widgets.load()
    FB.XFBML.parse()



do (DOMParser) ->
  "use strict"
  DOMParser_proto = DOMParser::
  real_parseFromString = DOMParser_proto.parseFromString

  try
    if (new DOMParser).parseFromString("", "text/html")
      # text/html parsing is natively supported
      return

  DOMParser_proto.parseFromString = (markup, type) ->
    if /^\s*text\/html\s*(?:;|$)/i.test(type)
      doc = document.implementation.createHTMLDocument("")
      if markup.toLowerCase().indexOf("<!doctype") > -1
        doc.documentElement.innerHTML = markup
      else
        doc.body.innerHTML = markup
      doc
    else
      real_parseFromString.apply this, arguments_

  return
