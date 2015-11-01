/**
 * Created by lorenzodonini on 22/10/15.
 */

function performSimpleAjaxRequest(value, target, handler) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            handler(xmlhttp.responseText);
        }
    };
    xmlhttp.open("POST", target, true);
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    xmlhttp.send(value);
}
