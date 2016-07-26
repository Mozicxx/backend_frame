var WinAlerts = window.alert;
window.alert = function(e) {
    if (e !== null && e.indexOf("www.miniui.com") > -1)
    {
        return false;
    }
    else
    {
        WinAlerts(e);
    }
};
function GetParams(url, c) {
    if (!url)
        url = location.href;
    if (!c)
        c = "?";
    url = url.split(c)[1];
    var params = {};
    if (url) {
        var us = url.split("&");
        for (var i = 0, l = us.length; i < l; i++) {
            var ps = us[i].split("=");
            params[ps[0]] = decodeURIComponent(ps[1]);
        }
    }
    return params;
}
