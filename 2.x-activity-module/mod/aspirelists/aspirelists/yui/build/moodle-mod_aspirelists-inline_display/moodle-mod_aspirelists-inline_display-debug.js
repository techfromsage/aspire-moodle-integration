YUI.add('moodle-mod_aspirelists-inline_display', function (Y, NAME) {

M.mod_aspirelists = M.mod_aspirelists || {};
NS = M.mod_aspirelists.inline_display = {};

NS.init_view = function(accordionOpen, accordionClosed) {
    Y.delegate('click', this.toggle_inline_list, Y.config.doc, '.aspirelists_inline_readings_toggle .activityinstance a', this);
    Y.on('domready', this.resize_embedded_lists);
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
        courseSection.all('.aspirelists_inline_readings_container').each(function(n)
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
    Y.all('.aspirelists_inline_list').each(function(o){
        o.setAttribute('width', o.ancestor('.aspirelists').getComputedStyle("width"));
    });
}


}, '@VERSION@', {"requires": ["base", "node", "event", "event-delegate"]});
