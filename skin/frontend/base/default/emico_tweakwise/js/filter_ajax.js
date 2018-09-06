var TweakwiseAjaxFilter;
(function() {
    TweakwiseAjaxFilter = function(options) {
        this.initialize(options);
    };

    TweakwiseAjaxFilter.prototype = {
        initialize: function(options) {
            this.options = {
                blocks: {},
                linkSelector: '.block-layered-nav a',
                cssLoadingClass: 'loading',
                cssLoaderOverlayClass: 'loader'
            };
            Object.extend(this.options, options);

            this.hookEvents();

            this.history = {};
        },

        hookEvents: function() {
            document.on('click', this.options.linkSelector, this.handleLinkClick.bind(this));
            window.onpopstate = this.handlePopState.bind(this);
        },

        handleLinkClick: function(event, element) {
            event.stop();

            var link = element.readAttribute('href');
            if (link.length === 0) {
                return false;
            }

            this.updateLink(link);
        },

        handlePopState: function(event) {
            this.updateLink(event.state, false);
        },

        updateLink: function(link, pushState) {
            pushState = typeof pushState === 'undefined' ? true : pushState;

            var result = this.history[link];
            if (result && result.blocks) {
                this.updateBlocks(result.blocks);
                if (result.title) {
                    this.setTitle(title)
                }

                if (pushState && history && history.pushState) {
                    history.pushState(link, result.title, link);
                }
            } else {
                this.sendAjaxRequest(link);
            }
        },

        handleAjaxResponse: function(link, data) {
            this.history[link] = data;
            this.updateLink(link);
        },

        addAjaxParam: function(link) {
            link += (link.split('?')[1] ? '&' : '?') + 'ajax=1';
            return link;
        },

        sendAjaxRequest: function(originalLink) {
            this.setBlocksLoading();
            if (this.runningRequest) {
                this.runningRequest.transport.abort();
            }

            var ajaxLink = this.addAjaxParam(originalLink);

            this.runningRequest = new Ajax.Request(ajaxLink, {
                method: 'get',
                onSuccess: function(response) {
                    var data;
                    if (response.responseJSON) {
                        data = response.responseJSON;
                    } else {
                        data = JSON.parse(response.responseText);
                    }

                    if (data.blocks) {
                        this.handleAjaxResponse(originalLink, data);
                    } else {
                        window.location.href = originalLink;
                    }
                }.bind(this),
                onFailure: function() {
                    window.location.href = originalLink;
                }.bind(this),
                onComplete: function() {
                    this.runningRequest = null;
                    this.hideBlocksLoading();
                }.bind(this)
            });
        },

        forEachBlock: function(callback) {
            $H(this.options.blocks).each(function(pair) {
                var name = pair.key;
                var selector = pair.value;

                $$(selector).each(function(element) {
                    callback(element, name, selector);
                });
            });
        },

        getElementOverlay: function(element) {
            var overlayClass = this.options.cssLoaderOverlayClass;
            if (!overlayClass) {
                return null;
            }

            var overlayElement = element.down(overlayClass);
            if (!overlayElement) {
                overlayElement = element.insert({
                    bottom: '<div class="' + overlayClass + '"></div>'
                }).down(overlayClass);
            }

            return overlayElement;
        },

        hideElementOverlay: function(element) {
            var overlay = this.getElementOverlay(element);
            if (overlay) {
                overlay.hide();
            }
        },

        showElementOverlay: function(element) {
            var overlay = this.getElementOverlay(element);
            if (overlay) {
                overlay.show();
            }
        },

        setBlocksLoading: function() {
            this.forEachBlock(function(element) {
                element.addClassName(this.options.cssLoadingClass);
                this.showElementOverlay(element);
            }.bind(this));
        },

        hideBlocksLoading: function() {
            this.forEachBlock(function(element) {
                element.removeClassName(this.options.cssLoadingClass);
                this.hideElementOverlay(element);
            }.bind(this));
        },

        triggerUpdateEvent: function() {
            document.fire('list:loaded');
        },

        updateBlocks: function(blocks) {
            this.forEachBlock(function(element, name) {
                if (blocks[name]) {
                    element.replace(blocks[name]);
                }
            }.bind(this));

            this.triggerUpdateEvent();
        }
    };
})();
