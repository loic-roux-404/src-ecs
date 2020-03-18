import * as $ from "jquery"

$('.connect-pop-toggle').on('click', function(e) {
   e.preventDefault()
   $('.pop-up-connection').toggleClass('showPop')
})
