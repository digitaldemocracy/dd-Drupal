/**
 * ajax loader for agendized bills
 **/
var AgendizedBillsLoader = (function () {
  var spinnerTmpl = '<div class="spinner">'
    + '<div class="bounce1"></div>'
    + '<div class="bounce2"></div>'
    + '<div class="bounce3"></div>'
    + '</div>';
  
  var AgendizedBillsLoader = function (hid, container) {
    var that = this;
    this.hid = hid;
    this.loaded = null;
    this.callback = null;
    this.container = jQuery(container);

    this.target = this.container;
  };

  AgendizedBillsLoader.prototype.load = function (callback) {
    if (this.loaded) {
      callback(this.loaded);
    }
    else {
      this.target.html("");
      // Create loading overlay
      this.spinner = 
        jQuery(spinnerTmpl).prependTo(this.target)
          .css('width', this.container.css('width'));
      console.log("loading agendized bills");
      var that = this;
      this.spinner.show();
      jQuery.post(drupalSettings.path.baseUrl + 'views/ajax', {
        view_name: 'hearing_components',
        view_display_id: 'agendized_bills',
        view_args: this.hid
      }, function (response) {
        that.spinner.hide();
        console.log("received agendized bills");
        console.log(response);
        // Response[3] is the element with the <div> for the view.
        if (typeof response[3] === 'object') {
          callback(response[3]);
        } else {
          that.target.html(
            "The server failed to respond within the set timeout period.<br/>"
            + "Click <a href='#' onclick='window.bills.loadAgendizedBills();'>"
            + "here</a> to load the data.");
        }
      });
    }
  }

  AgendizedBillsLoader.prototype.loadAgendizedBills = function () {
    var that = this;
    this.load(function (response) {
      var content = jQuery(response.data).find('.views-table.views-view-table');
      console.log(content);
      that.target.html(content);
      that.loaded = response;
      if (that.callback) {
        that.callback();
      }
    });
  };

  return AgendizedBillsLoader;
})();


