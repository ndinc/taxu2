$ ->
  app = new App()

class App
  constructor: ()->
    @$body = $('body')

    @initialize()

  initialize: ->

    @$body.imagesLoaded()
    .always ( instance ) =>
      @$body.removeClass 'is-loading'

      new Ga() if 'ga' in window
      @pjax = new Pjax()
      @layout = new Layout()

      @setFluidbox()
      # $(document).foundation()

      if @$body.hasClass 'home'
        @scrollAjax = new ScrollAjax '.js-articles'

        @scrollAjax.ajaxAfterFn = =>
          @pjax.init()

        @scrollAjax.scrollBeforeFn = =>
          # $.getScript 'http://fast.fonts.net/jsapi/eff36716-471f-44c4-bb6b-7962cd7f417b.js'
          @setFluidbox()
          @lazyMotion.setElems() if @lazyMotion

        @scrollAjax.ajaxFinishFn = =>
          @layout.offset = 1000


      # setTimeout =>
      #   @lazyMotion = new LazyMotion
      #     $target: $('.wp-content').find('img')
      #     offset: '20%'
      # ,1000
      $(window).on 'resize',Foundation.utils.debounce (e)=>
        # @layout.setMaxScroll()
        @scrollAjax.setMaxScroll() if @scrollAjax
      , 300


      $(window).on 'scroll', Foundation.utils.throttle (e)=>
        scrollTop = $(window).scrollTop()
        @layout.checkMenu scrollTop
        @lazyMotion.check scrollTop if @lazyMotion
        @scrollAjax.checkScrollPosition scrollTop if @scrollAjax
      , 100


      # http://foundation.zurb.com/docs/javascript.html
        # topbar :
        #   custom_back_text: false
        #   is_hover: true
        #   mobile_show_parent_link: true
    # twttr.widgets.load() if twttr
    # FB.XFBML.parse() if FB
  setFluidbox: ()->
    $('.article.is-active').find('a:has(img)').addClass 'fluidbox'
    $('.fluidbox').fluidbox
      viewportFill: 0.95


class Ga
  constructor: ->
    @attr = 'data-ga'
    @$el = $('['+@attr+']')
    @setClickEvent()

  setClickEvent: ()->
    @$el.on 'click', (e)=>
      $el = $(e.currentTarget)
      @send $el.attr @attr

  send: (query)->
    console.log query
    ga 'send','pageview', '/?js='+query



class Util
  isDirectionMobile: ()->
    isMobile = @userAgent.any()
    isMobileUrl = window.location.pathname.indexOf('/m/') != -1
    if (isMobile && !isMobileUrl)
      path = if(window.location.pathname.indexOf('animal=') != -1) then '/ohisama/m/detail.php' else '/ohisama/m'
      window.location.href = window.location.protocol + '//' + window.location.host + path  + window.location.pathname.replace('/ohisama','')
    else if !isMobile && isMobileUrl
      pathname = window.location.pathname.replace('detail.php','')
      window.location.href = window.location.protocol + '//' + window.location.host + pathname.replace('/m','')
    return

  userAgent:
    Android: ()->
      return navigator.userAgent.match(/Android/i)
    BlackBerry: ()->
      return navigator.userAgent.match(/BlackBerry/i)
    iOS: ()->
      return navigator.userAgent.match(/iPhone|iPad|iPod/i)
    Opera: ()->
      return navigator.userAgent.match(/Opera Mini/i)
    Windows: ()->
      return navigator.userAgent.match(/IEMobile/i)
    any: ()->
      return (@Android() || @BlackBerry() || @iOS() || @Opera() || @Windows())

  loadImage: (src)->
    dfd = $.Deferred()
    img = new Image()
    img.src = src
    $(img).imagesLoaded ->
      dfd.resolve()
    setTimeout ->
      dfd.resolve()
    , 30000
    return dfd.promise()


class ScrollAjax
  constructor: (el)->
    @el = el
    @$el = $(el)
    @paginateUrl = @$el.attr 'data-paginate'
    @$target = @$el.find('.is-active')
    @$nextTarget = @$target.next()
    @isLoading = false
    @scrollSpeed = 750
    @minLoadTime = 1500
    @setMaxScroll()
    @setContainerEl()

    unless @paginateUrl
      @$el.children().last().addClass 'is-loaded'


  setContainerEl: ->
    scrollTop = $(window).scrollTop()
    $(window).scrollTop scrollTop + 1
    if $('html').scrollTop() > 0
      @$container = $('html')
    else if $('body').scrollTop() > 0
      @$container = $('body')


  setMaxScroll: ->
    @maxScroll = $('#container').outerHeight(true) - $(window).height()

  checkScrollPosition: (scrollTop)->
    if @maxScroll <= scrollTop and !@isLoading
      @isLoading = true
      @$target.addClass 'is-loading'
      if @$nextTarget.length > 0
        setTimeout =>
          @activeNextTarget()
        , @minLoadTime
      else if @paginateUrl
        @ajaxLoadContent()
      else
        @$target.removeClass 'is-loading'
        @ajaxFinishFn() if @ajaxFinishFn

  ajaxLoadContent: ->
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
    $.when(
      loadAjax( @paginateUrl ),
      minTimeout()
    ).then (data)=>
      parser = new DOMParser()
      dom = parser.parseFromString(data, "text/html")
      $dom = $(dom)
      $el = $dom.find( @el )
      @$el.append $el.html()
      @paginateUrl = $el.attr 'data-paginate'
      @$nextTarget = @$target.next()
      @activeNextTarget()
      unless @paginateUrl
        @$el.children().last().addClass 'is-loaded'
      @ajaxAfterFn() if @ajaxAfterFn
    ,(e)->
      console.log e



  activeNextTarget: ()->
    @$target.removeClass 'is-loading'
    @$nextTarget.addClass 'is-active'
    nextTargetTop = @$nextTarget.offset().top
    @scrollBeforeFn() if @scrollBeforeFn
    @$container.animate
      'scrollTop': nextTargetTop
    , @scrollSpeed, =>
      @$container.scrollTop nextTargetTop - parseInt( @$target.find('.row').css 'margin-bottom' )
      @$target.addClass 'is-loaded'
      @isLoading = false
      @setMaxScroll()
      @$target = @$nextTarget
      @$nextTarget = @$target.next()
      @afterFn() if @afterFn





class Layout
  constructor: ->
    @navOffset = 500
    @$navigationContainer = $('.js-footer_nav')
    @$mainNavigation = @$navigationContainer.find('.js-footer_main_nav')
    @triggerClass = '.js-footer_nav_trigger'
    @$navTrigger = $(@triggerClass)
    @checkMenu()
    @setClickEvent()

  setClickEvent: ()->
    @$navTrigger.on 'click', (e)=>
      $el = $(e.currentTarget)
      $el.toggleClass 'menu-is-open'
      @$mainNavigation.off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend').toggleClass 'is-visible'
      return false

  checkMenu: (scrollTop)->
    if scrollTop > @navOffset and !@$navigationContainer.hasClass('is-fixed')
      @$navigationContainer.addClass('is-fixed')
      @$navTrigger.one 'webkitAnimationEnd oanimationend msAnimationEnd animationend', =>
        @$mainNavigation.addClass 'has-transitions'
        return
    else if scrollTop <= @navOffset
      if @$mainNavigation.hasClass('is-visible') and !$('html').hasClass('no-csstransitions')
        @$navTrigger.removeClass 'menu-is-open'
        @$mainNavigation.addClass('is-hidden').one 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', =>
          @$mainNavigation.removeClass 'is-visible is-hidden has-transitions'
          @$navigationContainer.removeClass 'is-fixed'
          return
      else if @$mainNavigation.hasClass('is-visible') and $('html').hasClass('no-csstransitions')
        @$mainNavigation.removeClass 'is-visible has-transitions'
        @$navigationContainer.removeClass 'is-fixed'
        @$navTrigger.removeClass 'menu-is-open'
      else
        @$navigationContainer.removeClass 'is-fixed'
        @$mainNavigation.removeClass 'has-transitions'
    return


    # ---
    # generated by js2coffee 2.0.0

# class Layout
#   constructor: ->
#     @$footer = $('#footer')
#     @footerClass = 'is-active'
#     @currentScrollPosition = 0
#     @offset = -100
#     @setMaxScroll()

#   setMaxScroll: ->
#     @maxScroll = $('#container').outerHeight(true) - $(window).height()

#   active: ->
#     @$footer.addClass @footerClass

#   inactive: ->
#     @$footer.removeClass @footerClass


#   setFooterClass: (scrollTop)->
#     if 10 <= @currentScrollPosition and @currentScrollPosition < scrollTop and @maxScroll > @currentScrollPosition
#       @inactive()
#     else if @maxScroll + @offset > @currentScrollPosition
#       @active()
#     @currentScrollPosition = scrollTop



