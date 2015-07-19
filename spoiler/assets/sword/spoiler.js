/**
 * JavaScript Spoiler
 * ------------------
 */

(function(w, d) {

    var panel = d.getElementsByClassName('spoiler');

    if (!panel) return;

    for (var i = 0, len = panel.length; i < len; ++i) {
        if (!panel[i].id) panel[i].id = 'spoiler-' + (i + 1);
    }

    function doToggleSpoiler(elem, index) {
        var toggle = d.createElement('a'),
            toggleText = (elem[index].getAttribute('data-toggle-text') || '&nbsp;').split(' | '),
            togglePlacement = elem[index].getAttribute('data-toggle-placement') && elem[index].getAttribute('data-toggle-placement') !== 'bottom' ? elem[index].getAttribute('data-toggle-placement') : 'bottom';
        toggleText.push(toggleText[0]);
        toggle.className = 'spoiler-toggle';
        toggle.href = '#' + elem[index].id;
        toggle.innerHTML = toggleText[/(^|\s)spoiler-state-collapsed(\s|$)/.test(elem[index].className) ? 0 : 1];
        toggle.onclick = function() {
            var target = this.parentNode,
                isExpanded = /(^|\s)spoiler-state-expanded(\s|$)/.test(target.className),
                isConnected = target.getAttribute('data-connection');
            if (/(^|\s)spoiler-state-disabled(\s|$)/.test(target.className)) return false;
            target.className = isExpanded ? target.className.replace(/(^|\s)spoiler-state-expanded(\s|$)/, '$1spoiler-state-collapsed$2') : target.className.replace(/(^|\s)spoiler-state-collapsed(\s|$)/, '$1spoiler-state-expanded$2');
            this.innerHTML = toggleText[isExpanded ? 0 : 1];
            if (isConnected) {
                for (var i = 0, len = elem.length; i < len; ++i) {
                    var isConnectedTo = elem[i].getAttribute('data-connection'),
                        toggleTextSibling = (elem[i].getAttribute('data-toggle-text') || '&nbsp;').split(' | '),
                        togglePlacementSibling = elem[i].getAttribute('data-toggle-placement') && elem[i].getAttribute('data-toggle-placement') !== 'bottom' ? elem[i].getAttribute('data-toggle-placement') : 'bottom';
                    if (isConnectedTo && isConnected === isConnectedTo && target.id !== elem[i].id) {
                        elem[i].className = elem[i].className.replace(/(^|\s)spoiler-state-expanded(\s|$)/, '$1spoiler-state-collapsed$2');
                        elem[i].children[togglePlacementSibling === 'bottom' ? 1 : 0].innerHTML = toggleTextSibling[0];
                    }
                }
            }
            return false;
        };
        toggle.onmousedown = false;
        elem[index].insertBefore(toggle, togglePlacement == 'bottom' ? null : elem[index].firstChild);
    }

    function doApplySpoiler() {
        for (var i = 0, len = panel.length; i < len; ++i) {
            doToggleSpoiler(panel, i);
        }
    }

    // Backend
    if (typeof DASHBOARD !== "undefined") {
        DASHBOARD.add('on_preview_complete', doApplySpoiler);
    }

    doApplySpoiler();

})(window, document);