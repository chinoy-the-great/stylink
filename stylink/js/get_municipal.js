$(document).ready(function () {
  // Load municipalities on page load
  loadMunicipalities();

  // When the municipality selection changes:
  $("#municipalities").on("change", function () {
    var municipality = $(this).val();
    if (municipality) {
      loadBarangays(municipality);
    } else {
      $("#barangays")
        .empty()
        .append('<option value="">Select Barangay</option>');
    }
  });
});

function loadMunicipalities() {
  $.ajax({
    url: "get_municipalities.php",
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#municipalities").append(
        $("<option>", {
          value: "",
          text: "Select Municipality",
        })
      );
      $.each(data, function (index, name) {
        $("#municipalities").append(
          $("<option>", {
            value: name,
            text: name,
          })
        );
      });
    },
  });
}

function loadBarangays(municipality) {
  $.ajax({
    url: "get_barangays.php",
    type: "GET",
    dataType: "json",
    data: {
      municipality: municipality,
    },
    success: function (data) {
      $("#barangays")
        .empty()
        .append('<option value="">Select Barangay</option>');
      $.each(data, function (index, name) {
        $("#barangays").append(
          $("<option>", {
            value: name,
            text: name,
          })
        );
      });
    },
  });
}
