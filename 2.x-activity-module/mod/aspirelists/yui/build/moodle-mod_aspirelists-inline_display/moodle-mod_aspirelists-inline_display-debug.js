YUI.add('moodle-mod_aspirelists-inline_display', function (Y, NAME) {

M.mod_aspirelists = M.mod_aspirelists || {};
NS = M.mod_aspirelists.inline_display = {};

NS.init_view = function(accordionOpen, accordionClosed) {
    Y.delegate('click', this.toggle_inline_list, Y.config.doc, '.aspirelists_inline_readings_toggle .activityinstance a', this);
    Y.on('domready', this.resize_embedded_lists);
    Y.on('domready', this.load_queued_iframes);
    this.accordionOpen = accordionOpen;
    this.accordionClosed = accordionClosed;
    this.iframeQueue = [];
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
                NS.toggleAccordionIndicator(n, NS.accordionOpen);
            } else {
                n.hide();
                NS.toggleAccordionIndicator(n, NS.accordionClosed);
            }
        });
    }
}

NS.resize_embedded_lists = function(e)
{
    console.log("resizeing");
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
}

NS.add_to_iframe_load_queue = function(src, element){
    this.iframeQueue.push({'src':src, 'element': element})
}

NS.processNextOnQueue = function(){
    var iframeData = this.iframeQueue.shift();
    console.log(iframeData);
    var src = iframeData['src'];
    var element = iframeData['element'];
    element.setAttribute('src', src);
    return element;
}

NS.waitForIframeToLoad = function(element, callback) {
    console.log(element);
    console.log("--")
    var win = Y.Node.getDOMNode(element.get('contentWindow'));
    var iframeDoc = win.document;
    // Check if loading is complete
    if (iframeDoc.readyState == 'complete') {
        win.onload = function () {
            console.log("done a thing");
            alert("hello");
            return callback();
        }
    }
    else {
        // If we are here, it is not loaded. Set things up so we check   the status again in 100 milliseconds
        window.setTimeout(NS.waitForIframeToLoad(element, callback), 100);
    }
}

NS.processQueue = function() {
    var element = NS.processNextOnQueue();
    NS.waitForIframeToLoad(element, function(){
        NS.processNextOnQueue();
    });
}

NS.load_queued_iframes = function(e){
    console.log("here");
    Y.all('.aspirelists_inline_list').each(function (o) {
        var src = o.getData('intended-src');
        NS.add_to_iframe_load_queue(src, o);
    });
    NS.processQueue();
}

}, '@VERSION@', {"requires": ["base", "node", "event", "event-delegate"]});
