jQuery(document).ready(function( $ ) {
// multi selecter on click
  window.onmousedown = function (e) {
      var el = e.target;
      if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {
          e.preventDefault();

          // toggle selection
          if (el.hasAttribute('selected')) el.removeAttribute('selected');
          else el.setAttribute('selected', '');

          // hack to correct buggy behavior
          var select = el.parentNode.cloneNode(true);
          el.parentNode.parentNode.replaceChild(select, el.parentNode);
      }
  }
// Disable unused fields
  var countChecked = function () {

    if  (document.getElementById("better_google_adsense_entire_site_1").checked ){
        $('#better_google_adsense_post_categories').attr('disabled', 'disabled'); //Disable
        $('#better_google_adsense_pages').attr('disabled', 'disabled'); //Disable
        $('#better_google_adsense_custom_post_type').attr('disabled', 'disabled'); //Disable

    } else {
      $('#better_google_adsense_post_categories').removeAttr('disabled'); //Enable
      $('#better_google_adsense_pages').removeAttr('disabled'); //Enable
      $('#better_google_adsense_custom_post_type').removeAttr('disabled'); //Enable
    }
};
countChecked();

$("input[type=checkbox]").on("click", countChecked);

});
