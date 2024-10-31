jQuery(document).ready(function($) {
  var frame;

  jQuery("#ogpanic_logo_id_button").on("click", function(event) {
    event.preventDefault();

    if (!frame) {
      // Create the media frame.
      frame = wp.media.frames.file_frame = wp.media({
        multiple: false
      });

      frame.on("select", function() {
        attachment = frame
          .state()
          .get("selection")
          .first()
          .toJSON();

        $("#image-preview")
          .attr("src", attachment.url)
          .css("width", "auto");
        $("#ogpanic_logo_id").val(attachment.id);
        console.log(attachment.id);
      });
    }
    // Finally, open the modal
    frame.open();
  });
});
