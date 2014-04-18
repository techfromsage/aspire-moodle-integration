M.mod_aspirelists = M.mod_aspirelists || {};
NS = M.mod_aspirelists.inline_display = {};

NS.init = function() {
    Y.delegate('click', this.show, Y.config.doc, SELECTORS.FRUIT, this);
};



NS.show = function(loc)
{
    console.log(loc + '_show');
//    Y.one(loc + '_show').hide();
    Y.one(loc).show();
}

M.mod_aspirelists.hide = function(loc)
{
    Y.one(loc).hide();
//    Y.one(loc + '_show').show();
}

//YUI().use('resize', 'resize-plugin', function (Y) {
//    var resize = new Y.Resize({
//        //Selector of the node to resize
//        node: '.aspire_inline_viewer'
//    });
//});
//// Resize the iframe to the
//M.mod_tadc.resize_iframe = function(Y, args)
//{
//    function resizeIframe()
//    {
//        Y.one('#tadc-bundle-viewer').setStyle('height', Y.DOM.winHeight());
//    }
//    Y.on("domready", resizeIframe, Y, "Resizing the iFrame");
//}