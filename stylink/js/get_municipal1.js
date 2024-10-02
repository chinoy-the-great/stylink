$(document).ready(function () {
  // Load 1 on page load
  loadMunicipalities();

  // When the municipality selection changes:
  $("#municipalities1").on("change", function () {
    var municipality = $(this).val();
    if (municipality) {
      loadBarangays(municipality);
    } else {
      $("#barangays1")
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
      $("#1").append(
        $("<option>", {
          value: "",
          text: "Select Municipality",
        })
      );
      $.each(data, function (index, name) {
        $("#1").append(
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
    url: "get_barangays1.php",
    type: "GET",
    dataType: "json",
    data: {
      municipality: municipality,
    },
    success: function (data) {
      $("#barangays1")
        .empty()
        .append('<option value="">Select Barangay</option>');
      $.each(data, function (index, name) {
        $("#barangays1").append(
          $("<option>", {
            value: name,
            text: name,
          })
        );
      });
    },
  });
}
