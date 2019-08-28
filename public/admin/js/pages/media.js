$(function () {
    'use strict';
});
function loadMoxman() {
    moxman.browse({
        view: 'thumbs',
        fullscreen: true,
        insert: false,
        close: false,
        sort_by: 'lastModified',
        sort_order: 'desc'
    });
}