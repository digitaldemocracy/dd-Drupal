(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.myclipbank = {
    attach: function (context, settings) {
      var clips = [];
      var editTagName = null;

      // Filter clips in bank by tag
      function filter(tag) {
        console.log(tag);
        jQuery('.bank-clip').hide();
        if (tag === undefined)
          jQuery('.bank-clip').show();
        else if (tag === 'none')
          jQuery('.bank-clip[data-tag=""]').show();
        else
          jQuery('.bank-clip[data-tag~="' + tag + '"]').show();
        // Fix the light dark pattern
        jQuery('.my-clip-list li').removeClass('light dark');
        jQuery('.my-clip-list li:visible:odd').addClass('light');
        jQuery('.my-clip-list li:visible:even').addClass('dark');

        jQuery('.view-myclip-bank .bank-clip').removeClass('light dark');
        jQuery('.view-myclip-bank .bank-clip:visible:odd').addClass('light');
        jQuery('.view-myclip-bank .bank-clip:visible:even').addClass('dark');
      }

      function setupTagClick() {
        filter();
        clips = JSON.parse(jQuery('#clips-json')[0].innerHTML);
        // set up on click for each filter tag
        jQuery('.cp-video-tag').click(function () {
          jQuery('.cp-video-tag').removeClass('cp-video-tag-active');
          jQuery(this).addClass('cp-video-tag-active');
          var tag = jQuery(this).attr('data-tag');
          if (tag === 'all')
            filter();
          else if (tag === 'none')
            filter('none');
          else
            filter(tag.slice(1 + tag.indexOf('-')));
        });
        consTable();
        consDialog();
      }

      // Constructs tag edit modal dialog
      function consDialog() {
        var dialog = jQuery('.cp-video-tags .dialog');
        dialog.dialog({
          autoOpen: false,
          modal: true,
          buttons: {
            Cancel: function () {
              dialog.dialog("close");
            },
            Save: function () {
              jQuery('#dd-video-editor-video-tags-edit-form input[name="clips"]')
                .val(JSON.stringify(clips));
              jQuery('#dd-video-editor-video-tags-edit-form').submit();
            }
          },
          resizable: false,
          title: 'Edit tags',
          width: 600
        });

        jQuery('.cp-video-tag-edit').click(function () {
          dialog.dialog('open');
        });
      }

      function buildClips(body) {
        for (var id in clips) {
          var clip = clips[id];
          var tags = clip.tags.map(function (val) {
            return val.replace('-', '--').replace(' ', '-');
          });
          var row = jQuery('<tr></tr>').attr(
            {'data-tags': tags.join(' '), 'data-clip': id}).appendTo(body);
          var check = jQuery('<input type="checkbox" />');

          jQuery('<td></td>').append(check).appendTo(row);
          jQuery('<td></td>').text(clip.name).appendTo(row);
          jQuery('<td></td>').text(clip.tags.join(', ')).appendTo(row);
        }
      }

      function resizeHeader() {
        var ws = []
        jQuery('#editTableBody tr').get(0).childNodes.forEach(function (val) {
          ws.push(jQuery(val).css('width'));
        });
        var cols = jQuery('#editTableHead th');
        for (var i = 0; i < ws.length; i++) {
          jQuery(cols[i]).css('width', ws[i]);
        }
      }

      // Constructs table used by dialog
      function consTable() {

        function consTag(tag) {
          var row = jQuery('<tr></tr>').attr('data-tag', tag.replace('-', '--')
            .replace(' ', '-')).appendTo(body);
          var editName = jQuery('<a></a>').text('edit name').click(function () {
            editNameDiv.show();
            editClipsDiv.hide();
            editDivHeader.text('Edit Existing Tag Name');

            editTag(this);
          });
          var editClips = jQuery('<a></a>').text('tag clips').click(function () {
            editNameDiv.hide();
            editClipsDiv.show();
            editDivHeader.text('Tag Clips');

            editTag(this);
          });
          var del = jQuery('<a></a>').text('delete').click(removeTag);
          jQuery('<td></td>').addClass('first').text(tag).appendTo(row);
          jQuery('<td></td>').append(editName).appendTo(row);
          jQuery('<td></td>').append(editClips).appendTo(row);
          jQuery('<td></td>').append(del).appendTo(row);
        }

        function editTag(target) {
          clipsBody.empty();
          buildClips(clipsBody);
          var tag = jQuery(target).closest('tr').attr('data-tag');
          editName.val(jQuery(target).closest('tr').find('.first').text());
          clipsBody.find('input').prop('checked', false);
          clipsBody.find('tr[data-tags~="' + tag + '"] input').prop('checked', true);
          editTagName = editName.val();
          editDiv.dialog('open');
          resizeHeader();
        }

        function removeTag() {
          editDiv.hide();
          var row = jQuery(this).closest('tr');
          var tagToRemove = row.find('.first').text();
          for (var id in clips) {
            clips[id].tags = clips[id].tags.filter(function (tag) {
              return tag !== tagToRemove;
            });
          }
          row.remove();
        }

        var tableDiv = jQuery('#table-div');
        var table = jQuery('<table></table>')
          .addClass('table table-condensed').appendTo(tableDiv);
        jQuery('<a></a>').text('Create New Tag').click(function () {
          editNameDiv.show();
          editClipsDiv.show();
          clipsBody.empty();
          buildClips(clipsBody);
          editDivHeader.text('Create New Tag');
          editName.val('');
          clipsBody.find('input').prop('checked', false);
          editTagName = null;
          editDiv.dialog('open');
          resizeHeader();
        }).appendTo(tableDiv);
        var notice = jQuery('<div></div>').addClass('text-warning')
          .text('To save changes, press save button').hide().appendTo(tableDiv);

        // Edit Table
        jQuery('body').find("#tag-dialog").remove();
        var editDiv = jQuery('<div id="tag-dialog"></div>')
          .appendTo(tableDiv).hide();
        editDiv.dialog({
          autoOpen: false,
          modal: true,
          buttons: {
            Cancel: function () {
              editDiv.dialog('close');
            },
            Done: done
          },
          resizable: false,
          title: 'Edit tags',
          width: 600
        });
        var editDivHeader = jQuery('<h3></h3>').appendTo(editDiv);

        var editNameDiv = jQuery('<div></div>').appendTo(editDiv);
        var editNameLabel = jQuery('<label>Tag name *</label>')
          .appendTo(editNameDiv);
        var editName = jQuery('<input></input>').appendTo(editNameDiv);

        var editClipsDiv = jQuery('<div></div>').appendTo(editDiv);
        jQuery('<b></b>').text('Select clips to tag *').appendTo(editClipsDiv);
        var editTableHead = jQuery('<table id="editTableHead"></table>')
          .addClass('table table-condensed').appendTo(editClipsDiv);
        var clipsHead = jQuery('<thead></thead>').appendTo(editTableHead);
        jQuery('<tr></tr>').appendTo(clipsHead).append(jQuery('<th></th>'))
          .append(jQuery('<th>Clip</th>')).append(jQuery('<th>Tags</th>'));
        var editTableBody = jQuery('<table id="editTableBody"></table>')
          .addClass('table table-condensed').appendTo(editClipsDiv);
        var clipsBody = jQuery('<tbody></tbody>').appendTo(editTableBody);

        function done() {
          var newTag = editName.val();
          var clipIds = [];
          jQuery.each(clipsBody.find('input'), function (i, val) {
            if (jQuery(val).prop('checked'))
              clipIds.push(jQuery(val).closest('tr').attr('data-clip'));
          });
          // check for validity
          var error = '';
          if (newTag.trim() === '')
            error += '\nTag name must be non-empty';
          if (clipIds.length === 0)
            error += '\nMust select at least one clip to attach tag';

          if (error !== '')
            alert(error);
          else {
            if (editTagName) {
              for (var id in clips) {
                clips[id].tags = clips[id].tags.filter(function (tag) {
                  return tag !== editTagName;
                });
              }
              // Edit Mode
              if (editTagName !== newTag) {
                var row = body.find('tr[data-tag=' + editTagName.replace('-', '--')
                    .replace(' ', '-') + ']')
                  .attr('data-tag', newTag.replace('-', '--').replace(' ', '-'));
                row.find('.first').text(newTag);
              }
              clipIds.forEach(function (id) {
                clips[id].tags.push(newTag);
              });
            }
            else {
              // Add Mode
              consTag(newTag);
              clipIds.forEach(function (id) {
                clips[id].tags.push(newTag);
              });
            }
            notice.show();
            editDiv.dialog('close');
          }
        }

        // Tags table
        var head = jQuery('<thead></thead>').appendTo(table);
        jQuery('<tr></tr>').appendTo(head).append(jQuery('<th>Tags</th>'))
          .append(jQuery('<th></th>')).append(jQuery('<th></th>'))
          .append(jQuery('<th></th>'));
        var body = jQuery('<tbody></tbody>').appendTo(table);
        jQuery('#user-tags-csv')
          .data('user-tags').split(',').forEach(function (tag) {
          consTag(tag);
        });
      }

      // Setup click handler for clip selection.
      jQuery('.my-clip-bank-area').each(function (index) {
        jQuery('.select-clip-button').on('click', function () {
          var clip_field_id = drupalSettings.campaign.clip_field_id;
          jQuery('[name="' + clip_field_id + '"]').val(jQuery(this).attr('data-clip-id'));
          jQuery(this).closest('.ui-dialog-content').dialog('close');
        });
        setupTagClick();
      });
    }
  };

})(Drupal, jQuery);
