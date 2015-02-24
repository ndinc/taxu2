// var LazyMotion;

// LazyMotion = (function() {
//   function LazyMotion(options) {
//     this.options = {
//       'attr': 'data-lazy',
//       '$el': $('html'),
//       '$target': null,
//       'scollEvent': false,
//       'position': 'bottom',
//       'offset': '100%',
//       'motion': this.addClass,
//       'reverse': this.removeClass,
//       'cls': 'is-active',
//       'beforeFn': function() {},
//       'afterFn': function() {}
//     };
//     $.extend(this.options, options);
//     this.$el = this.options.$target ? this.options.$target : $(this.options.$el).find('[' + this.options.attr + ']');
//     this.attr = this.options.attr;
//     this.cls = this.options.cls;
//     this.windowHeight = $(window).height();
//     this.moving = false;
//     this.init();
//   }

//   LazyMotion.prototype.init = function() {
//     var fn, key, _ref;
//     if (this.options.custom) {
//       _ref = this.options.custom;
//       for (key in _ref) {
//         fn = _ref[key];
//         if (!this[key]) {
//           this[key] = fn;
//         }
//       }
//     }
//     this.setElems();
//     this.check($(window).scrollTop());
//     if (this.scollEvent) {
//       $(window).on('scroll', (function(_this) {
//         return function() {
//           var scrollTop;
//           scrollTop = $(window).scrollTop();
//           return _this.check(scrollTop);
//         };
//       })(this));
//     }
//     return $(window).on('resize', (function(_this) {
//       return function() {
//         _this.windowHeight = $(window).height();
//         return _this.setBasePosition();
//       };
//     })(this));
//   };

//   LazyMotion.prototype.setElems = function() {
//     this.elems = [];
//     this.$el.each((function(_this) {
//       return function(i, el) {
//         var $el, attr, motionFn, motionParam, offset, position, reverseFn, reverseParam;
//         $el = $(el);
//         attr = $el.attr(_this.attr);
//         motionFn = _this.options.motion;
//         motionParam = _this.options.cls;
//         reverseFn = _this.options.reverse;
//         if (attr) {
//           if (attr.indexOf('!') !== -1) {
//             reverseFn = null;
//           }
//         }
//         reverseParam = _this.options.cls;
//         position = _this.options.position;
//         offset = $el.outerHeight(true) * parseInt(_this.options.offset) / 100;
//         if (attr) {
//           if (attr.indexOf('|') !== -1) {
//             attr = attr.split('|')[0];
//             reverseFn = attr.split('|')[1] ? attr.split('|')[1] : _this.options.reverse;
//           }
//         }
//         if (attr) {
//           $.each(attr.split(','), function(i, opt) {
//             var motionStr;
//             if (opt.indexOf('top:') !== -1 || opt.indexOf('center:') !== -1 || opt.indexOf('bottom:') !== -1) {
//               position = opt.split(':')[0];
//               return offset = opt.split(':')[1].indexOf('px') !== -1 ? parseInt(opt.split(':')[1]) : $el.outerHeight(true) * parseInt(opt.split(':')[1]) / 100;
//             } else {
//               motionStr = opt;
//               if (motionStr.indexOf(':') !== -1) {
//                 if (_this[motionStr.split(':')[0]]) {
//                   motionFn = _this[motionStr.split(':')[0]];
//                 }
//                 return motionParam = motionStr.split(':')[1];
//               } else {
//                 if (_this[motionStr]) {
//                   return motionFn = _this[motionStr];
//                 } else {
//                   return motionParam = motionStr;
//                 }
//               }
//             }
//           });
//         }
//         return _this.elems.push({
//           '$el': $el,
//           'offset': offset,
//           'position': position,
//           'positionTop': $el.offset().top,
//           'motion': {
//             'func': motionFn,
//             'param': motionParam
//           },
//           'reverse': {
//             'func': reverseFn,
//             'param': reverseParam
//           }
//         });
//       };
//     })(this));
//     this.setBasePosition();
//     return this.setMoved();
//   };

//   LazyMotion.prototype.setBasePosition = function() {
//     return $.each(this.elems, (function(_this) {
//       return function(i, elem) {
//         return elem.basePosition = (function() {
//           switch (elem.position) {
//             case 'top':
//               return 0 - elem.offset;
//             case 'center':
//               return this.windowHeight * 0.5 - elem.offset;
//             case 'bottom':
//               return this.windowHeight * 1 - elem.offset;
//           }
//         }).call(_this);
//       };
//     })(this));
//   };

//   LazyMotion.prototype.setMoved = function() {
//     var scrollTop;
//     scrollTop = $(window).scrollTop();
//     return $.each(this.elems, (function(_this) {
//       return function(i, elem) {
//         elem.moved = !_this.isMoved(elem, scrollTop);
//       };
//     })(this));
//   };

//   LazyMotion.prototype.isMoved = function(elem, scrollTop) {
//     return scrollTop + elem.basePosition > elem.positionTop;
//   };

//   LazyMotion.prototype.doMotion = function($el, motion) {
//     if (typeof motion === 'function') {
//       return motion($el);
//     } else if (motion.func) {
//       return motion.func($el, motion.param);
//     }
//   };

//   LazyMotion.prototype.check = function(scrollTop) {
//     return $.each(this.elems, (function(_this) {
//       return function(i, elem) {
//         if (_this.isMoved(elem, scrollTop)) {
//           if (!elem.moved) {
//             elem.moved = true;
//             return _this.doMotion(elem.$el, elem.motion);
//           }
//         } else if (elem.reverse.func) {
//           if (elem.moved) {
//             elem.moved = false;
//             return _this.doMotion(elem.$el, elem.reverse);
//           }
//         }
//       };
//     })(this));
//   };

//   LazyMotion.prototype.addClass = function($el, param) {
//     return $el.addClass(param);
//   };

//   LazyMotion.prototype.removeClass = function($el, param) {
//     return $el.removeClass(param);
//   };

//   LazyMotion.prototype.fadein = function($el, param) {
//     if (param == null) {
//       param = 200;
//     }
//     return $el.fadeIn(param);
//   };

//   return LazyMotion;

// })();

// var Pjax,
//   __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

// Pjax = (function() {
//   function Pjax(options) {
//     this.setPopStatsEvent = __bind(this.setPopStatsEvent, this);
//     this.options = {
//       'el': '.js-pjax_container',
//       'cls': 'js-pjax_container',
//       'anchor': 'a',
//       'beforeFn': function() {},
//       'afterFn': function() {},
//       'loadScript': true,
//       'loadLink': true,
//       'scrollSpeed': 500,
//       'bgimageAttr': '[data-bgimage]'
//     };
//     $.extend(this.options, options);
//     this.mainEl = this.options.el;
//     this.$mainEl = $(this.mainEl);
//     this.moving = false;
//     this.minLoadTime = this.options.scrollSpeed > 500 ? this.options.scrollSpeed + 200 : 700;
//     this.$title = $('title');
//     this.$body = $('body');
//     if ('pushState' in window.history) {
//       this.init(true);
//     }
//   }

//   Pjax.prototype.init = function() {
//     this.$mainEl.addClass(this.options.cls);
//     this.initAnchor();
//     this.setPopStatsEvent();
//     return this.loadLink('init');
//   };

//   Pjax.prototype.initAnchor = function() {
//     this.setAnchor();
//     return this.setClickEvent();
//   };

//   Pjax.prototype.setAnchor = function() {
//     var $anchor, anchors;
//     anchors = [];
//     $anchor = $(this.options.anchor);
//     $anchor.each((function(_this) {
//       return function(i, el) {
//         var url;
//         url = $(el).attr("href");
//         if (_this.isSelfDomain(url) && url.match(/jpg|jpeg|png|gif/g) === null && url.indexOf('javascript:') === -1 && url !== '#' && url.indexOf('mailto:') === -1) {
//           return anchors.push(el);
//         }
//       };
//     })(this));
//     return this.$anchors = $(anchors);
//   };

//   Pjax.prototype.isSelfDomain = function(url) {
//     return url && (url.indexOf(window.location.host) !== -1 || url.indexOf('http') === -1);
//   };

//   Pjax.prototype.setClickEvent = function(callback) {
//     this.nextFn = callback ? callback : this.defaultCallback;
//     this.$anchors.off("click");
//     return this.$anchors.on("click", (function(_this) {
//       return function(e) {
//         var href;
//         if (_this.moving === false) {
//           href = $(e.currentTarget).attr('href');
//           if (href !== window.location.href) {
//             _this.moving = true;
//             _this.nextFn(href, false);
//           }
//         }
//         return false;
//       };
//     })(this));
//   };

//   Pjax.prototype.setPopStatsEvent = function(callback) {
//     this.prevFn = callback ? callback : this.defaultCallback;
//     return $(window).on('popstate', (function(_this) {
//       return function(e) {
//         var url;
//         url = window.location.href;
//         if (_this.isSelfDomain(url)) {
//           _this.prevFn(url, true);
//           return false;
//         }
//       };
//     })(this));
//   };

//   Pjax.prototype.loadLink = function($dom) {
//     var init;
//     if (!$dom) {
//       return false;
//     }
//     init = $dom === 'init' ? true : false;
//     $dom = init ? $('html') : $dom;
//     this.stylesheets = this.stylesheets ? this.stylesheets : [];
//     return $dom.find('[rel=stylesheet]').each((function(_this) {
//       return function(i, el) {
//         var $el, href;
//         $el = $(el);
//         href = $el.attr('href');
//         if ($.inArray(href, _this.stylesheets) === -1) {
//           _this.stylesheets.push(href);
//           if (!init) {
//             return _this.$body.append('<link rel="stylesheet" href="' + href + '" type="text/css">');
//           }
//         }
//       };
//     })(this));
//   };

//   Pjax.prototype.loadScript = function($dom) {
//     if (!$dom) {
//       return false;
//     }
//     $('*').off();
//     $(window).off();
//     return $dom.find('script').each((function(_this) {
//       return function(i, el) {
//         var $el, src;
//         $el = $(el);
//         src = $el.attr('src');
//         if (src) {
//           _this.$body.append('<script type="text/javascript" src="' + src + '"></script>');
//         }
//         if ($el.text()) {
//           return _this.$body.append('<script type="text/javascript">' + $el.text() + '</script>');
//         }
//       };
//     })(this));
//   };

//   Pjax.prototype.loadContent = function(url, isPopStatsEvent, callback) {
//     var dfd, loadAjax, minTimeout;
//     loadAjax = function(url) {
//       var dfd;
//       dfd = $.Deferred();
//       return $.ajax({
//         url: url,
//         dataType: "html",
//         beforeSend: function(xhr) {
//           return xhr.setRequestHeader("X-PJAX", "true");
//         }
//       }).then(function(data) {
//         return dfd.resolve(data);
//       }, function(e) {

//         return dfd.reject();
//       });
//     };
//     minTimeout = (function(_this) {
//       return function() {
//         var dfd;
//         dfd = $.Deferred();
//         setTimeout(function() {
//           return dfd.resolve();
//         }, _this.minLoadTime);
//         return dfd.promise();
//       };
//     })(this);
//     if (!callback) {
//       dfd = $.Deferred();
//     }
//     $.when(loadAjax(url), minTimeout()).done((function(_this) {
//       return function(data) {
//         var $dom, bodyClass, dom, mainHtmls, parser, title;
//         parser = new DOMParser();
//         dom = parser.parseFromString(data, "text/html");
//         $dom = $(dom);
//         title = $dom.find("title").text();
//         bodyClass = $dom.find("body").attr('class');
//         mainHtmls = {};
//         $dom.find(_this.mainEl).each(function(i, el) {
//           var key;
//           key = $(el).attr('data-pjax') ? $(el).attr('data-pjax') : 'pjax-default-' + i;
//           return mainHtmls[key] = $(el).html();
//         });
//         if (!isPopStatsEvent) {
//           window.history.pushState(null, title, url);
//           if (ga) {
//             ga('send', 'pageview', '/?pjax=' + url);
//           }
//         }
//         _this.$title.html(title);
//         _this.$body.attr('class', bodyClass);
//         if (_this.options.loadLink) {
//           _this.loadLink($dom);
//         }
//         if (_this.options.loadScript) {
//           _this.loadScript($dom);
//         }
//         _this.moving = false;
//         if (callback) {
//           return callback(mainHtmls);
//         } else {
//           return dfd.resolve(mainHtmls);
//         }
//       };
//     })(this)).fail((function(_this) {
//       return function() {
//         _this.moving = false;
//         if (!callback) {
//           return dfd.reject();
//         }
//       };
//     })(this));
//     if (!callback) {
//       return dfd.promise();
//     }
//   };

//   Pjax.prototype.defaultCallback = function(url, isPopStatsEvent) {
//     if (url.slice('#')[0] === window.location.href && !isPopStatsEvent) {
//       this.moving = false;
//       return false;
//     }
//     this.defaultBeforeFn();
//     this.options.beforeFn();
//     return this.loadContent(url, isPopStatsEvent).done((function(_this) {
//       return function(htmls) {
//         if (htmls) {
//           _this.$mainEl.each(function(i, el) {
//             var key;
//             key = $(el).attr('data-pjax') ? $(el).attr('data-pjax') : 'pjax-default-' + i;
//             return $(el).html(htmls[key]);
//           });
//           $(_this.options.bgimageAttr).each(function(i, el) {
//             var src;
//             src = $(el).css('background-image').replace(/"|'/g, '').replace(/url\(|\)$/ig, '');
//             return _this.$mainEl.prepend($('<img>').attr('src', src).hide());
//           });
//           _this.$mainEl.imagesLoaded().always(function(instance) {
//             _this.defaultAfterFn();
//             _this.options.afterFn();
//           }).done(function(instance) {}).fail(function() {}).progress(function(instance, image) {});
//         } else {
//           _this.options.afterFn();
//         }
//       };
//     })(this)).fail(function() {
//       return window.location.href = url;
//     });
//   };

//   Pjax.prototype.defaultBeforeFn = function() {
//     this.$body.addClass('is-loading');
//     if (this.options.scrollSpeed && this.options.scrollSpeed > 0) {
//       return $('html,body').animate({
//         scrollTop: 0
//       }, this.options.scrollSpeed, function() {
//         return $(window).scrollTop(0);
//       });
//     } else {
//       return setTimeout(function() {
//         return $(window).scrollTop(0);
//       }, this.options.scrollSpeed);
//     }
//   };

//   Pjax.prototype.defaultAfterFn = function() {
//     this.$body.removeClass('is-loading');
//     twttr.widgets.load();
//     return FB.XFBML.parse();
//   };

//   return Pjax;

// })();

// (function(DOMParser) {
//   "use strict";
//   var DOMParser_proto, real_parseFromString;
//   DOMParser_proto = DOMParser.prototype;
//   real_parseFromString = DOMParser_proto.parseFromString;
//   try {
//     if ((new DOMParser).parseFromString("", "text/html")) {
//       return;
//     }
//   } catch (_error) {}
//   DOMParser_proto.parseFromString = function(markup, type) {
//     var doc;
//     if (/^\s*text\/html\s*(?:;|$)/i.test(type)) {
//       doc = document.implementation.createHTMLDocument("");
//       if (markup.toLowerCase().indexOf("<!doctype") > -1) {
//         doc.documentElement.innerHTML = markup;
//       } else {
//         doc.body.innerHTML = markup;
//       }
//       return doc;
//     } else {
//       return real_parseFromString.apply(this, arguments_);
//     }
//   };
// })(DOMParser);

// var App, Ga, Layout, ScrollAjax, Util,
//   __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

// $(function() {
//   var app;
//   return app = new App();
// });

// App = (function() {
//   function App() {
//     this.$body = $('body');
//     this.initialize();
//   }

//   App.prototype.initialize = function() {
//     return this.$body.imagesLoaded().always((function(_this) {
//       return function(instance) {
//         _this.$body.removeClass('is-loading');
//         if (__indexOf.call(window, 'ga') >= 0) {
//           new Ga();
//         }
//         _this.pjax = new Pjax();
//         _this.layout = new Layout();
//         _this.setFluidbox();
//         if (_this.$body.hasClass('home')) {
//           _this.scrollAjax = new ScrollAjax('.js-articles');
//           _this.scrollAjax.ajaxAfterFn = function() {
//             return _this.pjax.init();
//           };
//           _this.scrollAjax.scrollBeforeFn = function() {
//             _this.setFluidbox();
//             if (_this.lazyMotion) {
//               return _this.lazyMotion.setElems();
//             }
//           };
//           _this.scrollAjax.ajaxFinishFn = function() {
//             return _this.layout.offset = 1000;
//           };
//         }
//         $(window).on('resize', Foundation.utils.debounce(function(e) {
//           if (_this.scrollAjax) {
//             return _this.scrollAjax.setMaxScroll();
//           }
//         }, 300));
//         return $(window).on('scroll', Foundation.utils.throttle(function(e) {
//           var scrollTop;
//           scrollTop = $(window).scrollTop();
//           _this.layout.checkMenu(scrollTop);
//           if (_this.lazyMotion) {
//             _this.lazyMotion.check(scrollTop);
//           }
//           if (_this.scrollAjax) {
//             return _this.scrollAjax.checkScrollPosition(scrollTop);
//           }
//         }, 100));
//       };
//     })(this));
//   };

//   App.prototype.setFluidbox = function() {
//     $('.article.is-active').find('a:has(img)').addClass('fluidbox');
//     return $('.fluidbox').fluidbox({
//       viewportFill: 0.95
//     });
//   };

//   return App;

// })();

// Ga = (function() {
//   function Ga() {
//     this.attr = 'data-ga';
//     this.$el = $('[' + this.attr + ']');
//     this.setClickEvent();
//   }

//   Ga.prototype.setClickEvent = function() {
//     return this.$el.on('click', (function(_this) {
//       return function(e) {
//         var $el;
//         $el = $(e.currentTarget);
//         return _this.send($el.attr(_this.attr));
//       };
//     })(this));
//   };

//   Ga.prototype.send = function(query) {

//     return ga('send', 'pageview', '/?js=' + query);
//   };

//   return Ga;

// })();

// Util = (function() {
//   function Util() {}

//   Util.prototype.isDirectionMobile = function() {
//     var isMobile, isMobileUrl, path, pathname;
//     isMobile = this.userAgent.any();
//     isMobileUrl = window.location.pathname.indexOf('/m/') !== -1;
//     if (isMobile && !isMobileUrl) {
//       path = window.location.pathname.indexOf('animal=') !== -1 ? '/ohisama/m/detail.php' : '/ohisama/m';
//       window.location.href = window.location.protocol + '//' + window.location.host + path + window.location.pathname.replace('/ohisama', '');
//     } else if (!isMobile && isMobileUrl) {
//       pathname = window.location.pathname.replace('detail.php', '');
//       window.location.href = window.location.protocol + '//' + window.location.host + pathname.replace('/m', '');
//     }
//   };

//   Util.prototype.userAgent = {
//     Android: function() {
//       return navigator.userAgent.match(/Android/i);
//     },
//     BlackBerry: function() {
//       return navigator.userAgent.match(/BlackBerry/i);
//     },
//     iOS: function() {
//       return navigator.userAgent.match(/iPhone|iPad|iPod/i);
//     },
//     Opera: function() {
//       return navigator.userAgent.match(/Opera Mini/i);
//     },
//     Windows: function() {
//       return navigator.userAgent.match(/IEMobile/i);
//     },
//     any: function() {
//       return this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows();
//     }
//   };

//   Util.prototype.loadImage = function(src) {
//     var dfd, img;
//     dfd = $.Deferred();
//     img = new Image();
//     img.src = src;
//     $(img).imagesLoaded(function() {
//       return dfd.resolve();
//     });
//     setTimeout(function() {
//       return dfd.resolve();
//     }, 30000);
//     return dfd.promise();
//   };

//   return Util;

// })();

// ScrollAjax = (function() {
//   function ScrollAjax(el) {
//     this.el = el;
//     this.$el = $(el);
//     this.paginateUrl = this.$el.attr('data-paginate');
//     this.$target = this.$el.find('.is-active');
//     this.$nextTarget = this.$target.next();
//     this.isLoading = false;
//     this.scrollSpeed = 750;
//     this.minLoadTime = 1500;
//     this.setMaxScroll();
//     this.setContainerEl();
//     if (!this.paginateUrl) {
//       this.$el.children().last().addClass('is-loaded');
//     }
//   }

//   ScrollAjax.prototype.setContainerEl = function() {
//     var scrollTop;
//     scrollTop = $(window).scrollTop();
//     $(window).scrollTop(scrollTop + 1);
//     if ($('html').scrollTop() > 0) {
//       return this.$container = $('html');
//     } else if ($('body').scrollTop() > 0) {
//       return this.$container = $('body');
//     }
//   };

//   ScrollAjax.prototype.setMaxScroll = function() {
//     return this.maxScroll = $('#container').outerHeight(true) - $(window).height();
//   };

//   ScrollAjax.prototype.checkScrollPosition = function(scrollTop) {
//     if (this.maxScroll <= scrollTop && !this.isLoading) {
//       this.isLoading = true;
//       this.$target.addClass('is-loading');
//       if (this.$nextTarget.length > 0) {
//         return setTimeout((function(_this) {
//           return function() {
//             return _this.activeNextTarget();
//           };
//         })(this), this.minLoadTime);
//       } else if (this.paginateUrl) {
//         return this.ajaxLoadContent();
//       } else {
//         this.$target.removeClass('is-loading');
//         if (this.ajaxFinishFn) {
//           return this.ajaxFinishFn();
//         }
//       }
//     }
//   };

//   ScrollAjax.prototype.ajaxLoadContent = function() {
//     var loadAjax, minTimeout;
//     loadAjax = function(url) {
//       var dfd;
//       dfd = $.Deferred();
//       return $.ajax({
//         url: url,
//         dataType: "html",
//         beforeSend: function(xhr) {
//           return xhr.setRequestHeader("X-PJAX", "true");
//         }
//       }).then(function(data) {
//         return dfd.resolve(data);
//       }, function(e) {

//         return dfd.reject();
//       });
//     };
//     minTimeout = (function(_this) {
//       return function() {
//         var dfd;
//         dfd = $.Deferred();
//         setTimeout(function() {
//           return dfd.resolve();
//         }, _this.minLoadTime);
//         return dfd.promise();
//       };
//     })(this);
//     return $.when(loadAjax(this.paginateUrl), minTimeout()).then((function(_this) {
//       return function(data) {
//         var $dom, $el, dom, parser;
//         parser = new DOMParser();
//         dom = parser.parseFromString(data, "text/html");
//         $dom = $(dom);
//         $el = $dom.find(_this.el);
//         _this.$el.append($el.html());
//         _this.paginateUrl = $el.attr('data-paginate');
//         _this.$nextTarget = _this.$target.next();
//         _this.activeNextTarget();
//         if (!_this.paginateUrl) {
//           _this.$el.children().last().addClass('is-loaded');
//         }
//         if (_this.ajaxAfterFn) {
//           return _this.ajaxAfterFn();
//         }
//       };
//     })(this), function(e) {
//       return
//     });
//   };

//   ScrollAjax.prototype.activeNextTarget = function() {
//     var nextTargetTop;
//     this.$target.removeClass('is-loading');
//     this.$nextTarget.addClass('is-active');
//     nextTargetTop = this.$nextTarget.offset().top;
//     if (this.scrollBeforeFn) {
//       this.scrollBeforeFn();
//     }
//     return this.$container.animate({
//       'scrollTop': nextTargetTop
//     }, this.scrollSpeed, (function(_this) {
//       return function() {
//         _this.$container.scrollTop(nextTargetTop - parseInt(_this.$target.find('.row').css('margin-bottom')));
//         _this.$target.addClass('is-loaded');
//         _this.isLoading = false;
//         _this.setMaxScroll();
//         _this.$target = _this.$nextTarget;
//         _this.$nextTarget = _this.$target.next();
//         if (_this.afterFn) {
//           return _this.afterFn();
//         }
//       };
//     })(this));
//   };

//   return ScrollAjax;

// })();

// Layout = (function() {
//   function Layout() {
//     this.navOffset = 500;
//     this.$navigationContainer = $('.js-footer_nav');
//     this.$mainNavigation = this.$navigationContainer.find('.js-footer_main_nav');
//     this.triggerClass = '.js-footer_nav_trigger';
//     this.$navTrigger = $(this.triggerClass);
//     this.checkMenu();
//     this.setClickEvent();
//   }

//   Layout.prototype.setClickEvent = function() {
//     return this.$navTrigger.on('click', (function(_this) {
//       return function(e) {
//         var $el;
//         $el = $(e.currentTarget);
//         $el.toggleClass('menu-is-open');
//         _this.$mainNavigation.off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend').toggleClass('is-visible');
//         return false;
//       };
//     })(this));
//   };

//   Layout.prototype.checkMenu = function(scrollTop) {
//     if (scrollTop > this.navOffset && !this.$navigationContainer.hasClass('is-fixed')) {
//       this.$navigationContainer.addClass('is-fixed');
//       this.$navTrigger.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', (function(_this) {
//         return function() {
//           _this.$mainNavigation.addClass('has-transitions');
//         };
//       })(this));
//     } else if (scrollTop <= this.navOffset) {
//       if (this.$mainNavigation.hasClass('is-visible') && !$('html').hasClass('no-csstransitions')) {
//         this.$navTrigger.removeClass('menu-is-open');
//         this.$mainNavigation.addClass('is-hidden').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', (function(_this) {
//           return function() {
//             _this.$mainNavigation.removeClass('is-visible is-hidden has-transitions');
//             _this.$navigationContainer.removeClass('is-fixed');
//           };
//         })(this));
//       } else if (this.$mainNavigation.hasClass('is-visible') && $('html').hasClass('no-csstransitions')) {
//         this.$mainNavigation.removeClass('is-visible has-transitions');
//         this.$navigationContainer.removeClass('is-fixed');
//         this.$navTrigger.removeClass('menu-is-open');
//       } else {
//         this.$navigationContainer.removeClass('is-fixed');
//         this.$mainNavigation.removeClass('has-transitions');
//       }
//     }
//   };

//   return Layout;

// })();
