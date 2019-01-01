(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.campaigntargeting = {
    attach: function (context, settings) {
      $.fn.updateLegislators = function () {
        var house = $('#edit-legislators-filter-by-house').val();
        var party = $('#edit-legislators-filter-by-party').val();
        var committee_name = jQuery('#edit-legislators-filter-by-committee').val();
        $.ajax({
          url: '/target_legislators/' + house + '/' + party + '/all'
        }).done(function (data) {
          $('#edit-legislators-available').empty();
          $.each(data.legislators, function (key, value) {
            $('#edit-legislators-available').append(jQuery('<option></option>').attr('value', value.pid).text(value.last + ', ' + value.first));
          });
        });
      };
      $.fn.updateCommitteeMembers = function () {
        var house = $('#edit-legislators-filter-by-house').val();
        var party = $('#edit-legislators-filter-by-party').val();
        $.ajax({
          url: '/target_legislators/' + house + '/' + party + '/all'
        }).done(function (data) {
          $('#edit-legislators-available').empty();
          $.each(data.legislators, function (key, value) {
            $('#edit-legislators-available').append(jQuery('<option></option>').attr('value', value.pid).text(value.last + ', ' + value.first));
          });
        });
      };
      $.fn.updateCommittees = function () {
        var house = $('#edit-committee-members-filter-by-house').val();
        $.ajax({
          url: '/target_committees/' + house
        }).done(function (data) {
          var filter = $('#edit-committee-members-filter-by-committee');
          filter.empty();
          $('#edit-committee-members-filter-by-committee').append(jQuery('<option></option>').attr('value', '').text('Select a Committee'));
          $.each(data.committees, function (key, value) {
            $('#edit-committee-members-filter-by-committee').append(jQuery('<option></option>').attr('value', value.cid).text(value.name));
          });

          filter.trigger('chosen:updated');
        });
      };

      $.fn.updateCommitteeMembers = function () {
        var committee_field = jQuery('#edit-committee-members-filter-by-committee option:selected');
        var committee_name = committee_field.text();
        var committee_cid = committee_field.val();
        var house = $('#edit-legislators-filter-by-house').val();

        $.ajax({
          url: '/target_legislators/' + house + '/all/' + committee_name
        }).done(function (data) {
          $('#edit-committee-members-available').empty();
          $.each(data.legislators, function (key, value) {
            $('#edit-committee-members-available').append(jQuery('<option></option>').attr('value', value.pid + ':' + committee_cid).text(value.last + ', ' + value.first + ' (' + committee_name + ')'));
          });
        });
      };

      // Set the checkboxes based on target_ids hidden field.
      $.fn.initTargetCheckboxes = function () {
        var target_ids_string = $("input[name='target_ids']").val();
        if (target_ids_string) {
          var target_ids = target_ids_string.split(',');
          for (var index in target_ids) {
            $("input[name='targets[]'][value='" + target_ids[index] + "']").attr('checked', 'checked');
          }
        }
      };

      $.fn.updateTargetIds = function () {
        var target_ids = [];
        $("input[name='targets[]']:checked").each(function () {
          target_ids.push($(this).val());
        });

        $("input[name='target_ids']").val(target_ids.join());

        target_ids.length ? $('#field-actions-add-more-wrapper').removeClass('disable-form') : $('#field-actions-add-more-wrapper').addClass('disable-form');
        target_ids.length ? $('#edit-no-targets-selected').hide() : $('#edit-no-targets-selected').show();
      };

      $('#edit-legislators-filter-by-house').change(function () { $.fn.updateLegislators(); });
      $('#edit-legislators-filter-by-party').change(function () { $.fn.updateLegislators(); });
      $('#edit-committee-members-filter-by-house').change(function () { $.fn.updateCommittees(); });
      $('#edit-committee-members-filter-by-committee').change(function () { $.fn.updateCommitteeMembers(); });
      $("#block-views-block-campaign-wizard-targets-block-1 input[name='targets[]']").change(function () { $.fn.updateTargetIds(); });

      if ($('.campaign-wizard--target-actions').length && $('#statewide_campaign').val() === '0') {
        $.fn.initTargetCheckboxes();

        // Check if no actions are available to assign.
        if ($('#field-actions-add-more-wrapper').length && $("input[name='targets[]']").length === 0) {
          $('#edit-no-targets-available').show();
          $('#field-actions-add-more-wrapper').hide();
          $('#edit-no-targets-selected').hide();
        }
      }
      // Setup tabs on target actions form.
      $('#assign-duplicate-group-tabs').tabs();

      // Nav Save For Later button.
      $('#save-finish-later-button').click(function () {
        switch (drupalSettings.campaign_wizard_step) {
          case 'campaign-wizard--preview':
          case 'campaign-wizard--target-actions':
          case 'campaign-wizard--review':
            window.location = '/user/' + drupalSettings.user.uid + '/campaigns';
            break;

          case 'campaign-wizard--choose-targets':
            $('#edit-save-for-later').trigger('click');
            break;

          default:
            $('#node-campaign-edit-form').attr('action', $('#node-campaign-edit-form').attr('action') + '?destination=/user/' + drupalSettings.user.uid + '/campaigns');
            $('#edit-submit').trigger('click');
        }
      });

      // Assign Action button.
      $('#edit-field-actions-add-more-group-tabs-wrapper-assign-existing-group-wrapper-action-group-assign-button').click(function () {
        var target_ids = $("input[name='target_ids']").val();
        if (!target_ids.length) {
          alert('No target(s) selected');
          return false;
        }
        var campaign_action_id = $('#edit-field-actions-add-more-group-tabs-wrapper-assign-existing-group-wrapper-action-group-id').val();
        $(this).attr('href', $(this).attr('href') + '&target_ids=' + target_ids + '&campaign_action_id=' + campaign_action_id);
      });

      // Duplicate Action Group button.
      $('#edit-field-actions-add-more-group-tabs-wrapper-duplicate-existing-group-wrapper-duplicate-action-group-assign-button').click(function () {
        var target_ids = $("input[name='target_ids']").val();
        if (!target_ids.length) {
          alert('No target(s) selected');
          return false;
        }
        if (!$('#edit-field-actions-add-more-group-tabs-wrapper-duplicate-existing-group-wrapper-duplicate-group-name').val().length) {
          alert('No name entered!');
          return false;
        }
        var name = $('#edit-field-actions-add-more-group-tabs-wrapper-duplicate-existing-group-wrapper-duplicate-group-name').val();
        var campaign_action_id = $('#edit-field-actions-add-more-group-tabs-wrapper-duplicate-existing-group-wrapper-duplicate-action-group-id').val();
        $(this).attr('href', $(this).attr('href') + '&target_ids=' + target_ids + '&name=' + encodeURIComponent(name) + '&campaign_action_id=' + campaign_action_id);
      });

      // Check for duplicate campaign action id, open edit dialog.
      if (drupalSettings.duplicate_campaign_action_id) {
        $('#edit-group-' + drupalSettings.duplicate_campaign_action_id).trigger('click');
      }
    }
  };

  Drupal.behaviors.details_edit_close = {
    attach: function (context, settings) {
      $('.campaign-wizard details.details-edit-close').click(function () {
        var is_open = ($(this).attr('open') === 'open');

        $('.campaign-wizard details.details-edit-close').each(function () {
          $(this).removeAttr('open');
          $('.details-toggle', $(this)).html('Edit');
        });

        if (is_open) {
          $(this).attr('open', 'open');
          $('.details-toggle', $(this)).html('Edit');
        }
        else {
          $('.details-toggle', $(this)).html('Close');
        }
      });
    }
  };

  Drupal.behaviors.campaign_form_bill = {
    attach: function (context, settings) {
      $('#edit-field-bill-0-target-id', context).change(function () {
        setBillPreview($(this).val());
      });
    }
  };

  function setBillPreview(field_value) {
    var match = field_value.match(/\(([0-9]*?)\)$/);
    if (match && typeof match[1] !== 'undefined') {
      var bill_dr_id = match[1];
      getBillVersionCurrentData('/billversioncurrent/' + bill_dr_id).done(billCallbackHandler);
    }
  }
  function getBillVersionCurrentData(bid_url) {
    return $.ajax({
      url: bid_url,
      type: 'GET'
    });
  }

  /**
   * Transforms the data object into an array and update autocomplete results.
   *
   * @param {object} data
   *   The data sent back from the server.
   */
  function billCallbackHandler(data) {
    if (data) {
      var subject = data.subject[0].value;
      var title = data.title[0].value;
      var digest = data.digest[0].value;
      var status = data.billState[0].value;

      title = title.replace(/<\/?[^>]+(>|$)/g, '');
      $('.campaign-bill-preview').show();

      $('#campaign-bill-preview-title').html(title);
      $('#campaign-bill-preview-subject').html(subject);
      $('#campaign-bill-preview-status').html(status);
      $('#campaign-bill-preview-digest').html(digest);
    }
  }

  var bill_target = $('#edit-field-bill-0-target-id');
  if (bill_target.length) {
    setBillPreview(bill_target.val());
  }
})(Drupal, jQuery);

