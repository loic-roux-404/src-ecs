import * as $ from "jquery";

const btnSearch: JQuery<HTMLElement> = $('.input-diy');
const search: JQuery<HTMLElement> = $('.diy-bar');

search.on('click', function () {
    btnSearch.addClass('showBtn');
});
