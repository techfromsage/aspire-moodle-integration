M.mod_aspirelists = M.mod_aspirelists || {};
NS = M.mod_aspirelists.inline_display = {};

NS.init_view = function(accordionOpen, accordionClosed) {
    Y.delegate('click', this.toggle_inline_list, Y.config.doc, '.aspirelists_inline_readings_toggle .activityinstance a', this);
    Y.on('domready', this.resize_embedded_lists);
    Y.on('resize', this.resize_embedded_lists);
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
                if(n.getAttribute('src') === ''){
                    // if the iFrame's src has not yet been set then time to set it
                    var src = n.getData('intended-src');
                    n.setAttribute('src', src);
                }
                n.show();
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
