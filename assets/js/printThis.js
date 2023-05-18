/*
* printThis v1.3
* @desc Printing plug-in for jQuery
* @author Jason Day
* 
* Resources (based on) :
*   jPrintArea: http://plugins.jquery.com/project/jPrintArea
*   jqPrint:    https://github.com/permanenttourist/jquery.jqprint
*   Ben Nadal:  http://www.bennadel.com/blog/1591-Ask-Ben-Print-Part-Of-A-Web-Page-With-jQuery.htm
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*
* (c) Jason Day 2013
*
* Usage:
*
* $("#mySelector").printThis({
*   debug: false,               * Show the iframe for debugging.
*   importCSS: true,            * Import page CSS.
*   printContainer: true,       * Grab outer container as well as the contents
*                                 of the selector.
*   loadCSS: "path/to/my.css",  * Path to additional css file.
*   pageTitle: "",              * Add title to print page.
*   removeInline: false,        * Remove all inline styles from print elements.
*   printDelay: 333,            * Variable print delay.
*   header: null                * Prefix to html.
*  });
*
* Notes:
* - The loadCSS will load additional css (with or without @media print) into
*   the iframe, adjusting layout.
*/
;(function ($) {
  var opt;
  $.fn.printThis = function (options) {
    opt = $.extend({}, $.fn.printThis.defaults, options);
    var $element = this instanceof jQuery ? this : $(this);
    var strFrameName = "printThis-" + (new Date()).getTime();
    if(window.location.hostname !==
      document.domain &&
      navigator.userAgent.match(/msie/i)) {
      // Ugly IE hacks due to IE not inheriting document.domain from parent
      // checks if document.domain is set by comparing the host name against
      // document.domain.
      var iframeSrc = 
        "javascript:document.write(\"<head><script>document.domain=\\\"" +
        document.domain +
        "\\\";</script></head><body></body>\")";
      var printI = document.createElement('iframe');
      printI.name = "printIframe";
      printI.id = strFrameName;
      printI.className = "MSIE";
      document.body.appendChild(printI);
      printI.src = iframeSrc;
    } else {
      // Other browsers inherit document.domain, and IE works if
      // document.domain is not explicitly set.
      var $frame =
        $("<iframe id='" + strFrameName +"' name='printIframe' />");
      $frame.appendTo("body");
    }
    var $iframe = $("#" + strFrameName);
    // Show frame if in debug mode.
    if (!opt.debug) $iframe.css({
      position: "absolute",
      width: "0px",
      height: "0px",
      left: "-600px",
      top: "-600px"
    });

    // $iframe.ready() and $iframe.load were inconsistent between browsers. 
    setTimeout ( function () {
      var $doc = $iframe.contents();
      // Import page stylesheets.
      if (opt.importCSS) $("link[rel=stylesheet]").each(function () {
        var href = $(this).attr("href");
        if (href) {
          var media = $(this).attr("media") || "all";
          $doc.find("head").append(
            "<link type='text/css' rel='stylesheet' href='" +
            href +
            "' media='" +
            media +
            "'>"
          )
        }
      });
      
      // Add title of the page.
      if (opt.pageTitle) $doc.find("head").append(
        "<title>" +
          opt.pageTitle +
        "</title>"
      );
      
      // Import additional stylesheet.
      if (opt.loadCSS) $doc.find("head").append(
        "<link type='text/css' rel='stylesheet' href='" +
        opt.loadCSS +
        "'>"
      );
      
      // Print header.
      if (opt.header) $doc.find("body").append(opt.header);

      // Grab $.selector as container.
      if (opt.printContainer) $doc.find("body").append($element.outer());
        
      // Otherwise just print interior elements of container.
      else $element.each(function () {
        $doc.find("body").append($(this).html());
      });
      
      // Remove inline styles.
      if (opt.removeInline) {
        // $.removeAttr available jQuery 1.7+.
        if ($.isFunction($.removeAttr)) {
          $doc.find("body *").removeAttr("style");
        } else {
          $doc.find("body *").attr("style", "");
        }
      } 
      
      setTimeout(function () {
        if($iframe.hasClass("MSIE")) {
          // Check if the iframe was created with the ugly hack and perform
          // another ugly hack out of neccessity.
          window.frames["printIframe"].focus();
          $doc.find("head").append("<script>  window.print(); </script>");
        } else {
          // Proper method.
          $iframe[0].contentWindow.focus();
          $iframe[0].contentWindow.print();  
        }
        $element.trigger( "done");
        // Remove iframe after print.
        if (!opt.debug) {
          setTimeout(function () {
            $iframe.remove();
          }, 1000);
        }
      }, opt.printDelay);
    }, 333);
  };

  // Defaults.
  $.fn.printThis.defaults = {
    debug: false,         // Show the iframe for debugging.
    importCSS: true,      // Import parent page css.
    printContainer: true, // Print outer container/$.selector.
    loadCSS: "",          // Load an additional css file.
    pageTitle: "",        // Add title to print page.
    removeInline: false,  // Remove all inline styles.
    printDelay: 333,      // Variable print delay.
    header: null          // Prefix to html.
  };

  // $.selector container.
  jQuery.fn.outer = function () {
    return $($("<div></div>").html(this.clone())).html()
  }
})(jQuery);
/* 
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates and open the template
* in the editor.
*/
