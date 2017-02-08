YUI.add('moodle-mod_aspirelists-inline_display', function (Y, NAME) {

M.mod_aspirelists = M.mod_aspirelists || {};
NS = M.mod_aspirelists.inline_display = {};

NS.init_view = function(accordionOpen, accordionClosed) {
    Y.delegate('click', this.toggle_inline_list, Y.config.doc, '.aspirelists_inline_readings_toggle .activityinstance a', this);
    Y.on('domready', this.resize_embedded_lists);
    Y.on('domready', this.ExpandedInlineIFramesLoader.startQueue);
    Y.on('scroll', this.ExpandedInlineIFramesLoader.loadIfIframeInViewport);
    this.accordionOpen = accordionOpen;
    this.accordionClosed = accordionClosed;
};

NS.toggleAccordionIndicator = function(elem, indicatorText)
{
    elem.ancestor('.aspirelists').all('span.aspirelists_inline_accordion').each(function(n){
        n.setHTML(indicatorText);
    });
};

NS.toggle_inline_list = function(e)
{
    e.preventDefault();
    var courseSection = e.target.ancestor('.aspirelists');
    if(courseSection)
    {
        courseSection.all('.aspirelists_inline_list').each(function(n)
        {
            if(n.getStyle('display') === 'none')
            {
                n.show();
                if(n.getAttribute('src') === ''){
                    // if the iFrame's src has not yet been set then time to set it
                    var src = n.getData('intended-src');
                    n.setAttribute('src', src);
                }
                NS.toggleAccordionIndicator(n, NS.accordionOpen);
            } else {
                n.hide();
                NS.toggleAccordionIndicator(n, NS.accordionClosed);
            }
        });
    }
};

NS.resize_embedded_lists = function(e)
{
    Y.all('.aspirelists_inline_list').each(function(o){
        var width = o.ancestor('.aspirelists').get("offsetWidth");

        var margin = o.ancestor('.aspirelists').getComputedStyle("margin");
        var padding = o.ancestor('.aspirelists').getComputedStyle("padding-left");
        if(margin) { width = width - (parseFloat(margin, 10) * 2);}
        if(padding) { width = width - (parseFloat(padding, 10) * 2);}

        var indent = o.ancestor('.mod-indent-outer');
        if(indent)
        {
            var iPadding = indent.getComputedStyle("padding-left");
            var iMargin = o.getComputedStyle("margin-left");
            if(iMargin) { width = width - (parseFloat(iMargin, 10) * 2);}
            if(iPadding) { width = width - (parseFloat(iPadding, 10) * 2);}
        }

        o.setAttribute('width', width);
    });
};

/**
 * Handle expanded inline iFrames
 *
 * startQueue() will queue all Expanded inline iFrames to load sequentially,
 * each one paced by the inline_display_delay time (milliseconds)
 *
 * loadIfIframeInViewport() will detect if a queued-but-not-yet-loaded resource
 * is currently on screen.  If the user keeps it on screen for the inline_display_delay
 * time (milliseconds) then the launch will be triggered.  Typically used
 * as a handler for a scroll event.
 *
 * This will ignore any inline iframes which are
 * currently collapsed.  These will be
 * loaded when the user interacts with them.
 *
 * Call .init() to start the process
 */
NS.ExpandedInlineIFramesLoader = (function(){
    var thisScope;
    var loader = {
        iframeQueue: [],
        inline_display_delay: 1000,
        viewport_hover_delay: 1000,
        loadIfIframeInViewport: function(){
            // A scroll has happened, so check every queued iFrame
            // to see if it is visible, if so then wait for the value
            // of viewport_hover_delay, and if the element is still visible
            // then load it.
            for (var i = 0; i < thisScope.iframeQueue.length; i++) {
                var element = thisScope.iframeQueue[i]['element'];
                if (Y.DOM.inViewportRegion(element.getDOMNode())) {
                    var src = thisScope.iframeQueue[i]['src'];
                    window.setTimeout((function (element, src, populateIFrameCallback) {
                        return function () {
                            // If we are still looking at this DOM after a wait
                            // then load it
                            if (Y.DOM.inViewportRegion(element.getDOMNode())) {
                                populateIFrameCallback(src, element);
                            }
                        }
                    }(element, src, thisScope.populateIFrame)), thisScope.viewport_hover_delay);
                }
            }
        },
        startQueue: function() {
            // Add all inline elements to the queue;
            Y.all('.aspirelists_inline_list').each(function (o) {
                var src = o.getData('intended-src');
                if (o.getStyle('display') !== 'none') {
                    // Only queue expanded resources
                    thisScope.addToIframeLoadQueue(src, o);
                }
            });
            thisScope.processNextInQueue();
        },
        addToIframeLoadQueue: function (src, element) {
            this.iframeQueue.push({'src': src, 'element': element});
        },
        populateIFrame: function (src, element) {
            if (element.getAttribute('src') === '') {
                // if the iFrame's src has not yet been set then set it
                element.setAttribute('src', src);
            }
        },
        processNextInQueue: function () {
            // Process the first launch call
            var iFrameData = thisScope.iframeQueue.shift();
            if (iFrameData === undefined) {
                // In this case we have called all elements to load and can complete
                return;
            }
            this.populateIFrame(iFrameData.src, iFrameData.element);
            // Set up a timeout to continue iterating over the calls
            window.setTimeout(function () {
                thisScope.processNextInQueue();
            }, thisScope.inline_display_delay);
        },
        init: function(){
            thisScope = this;
        }
    };
    loader.init();
    return loader;
})();

}, '@VERSION@', {"requires": ["base", "node", "event", "event-delegate"]});
