// Adjust the min ending date range of
// specific date selection in revenue selection page
function updateMinRange() {
  var startInput = document.getElementById("startDate");
  try {
    var startDate = startInput.value;
    var endInput = document.getElementById("endDate");
    endInput.min = startDate;
  } catch (TypeError) {
    const today = new Date();
    var startDate = today.toISOString().split("T")[0];
  }
}

function switchDisable(status) {
  var startInput = document.getElementById("startDate");
  var endInput = document.getElementById("endDate");
  var revenueMode5 = document.getElementById("revenueMode5");
  if (revenueMode5.checked) {
    console.log("enable");
    startInput.disabled = false;
    endInput.disabled = false;
  } else {
    console.log("disable");
    startInput.disabled = true;
    endInput.disabled = true;
  }
}



window.onload = updateMinRange();
// window.onload(updateMinRange());
