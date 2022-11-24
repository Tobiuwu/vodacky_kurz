

$(document).ready(function () {
    $('#ship_video').bind('contextmenu', function () { return false; });
    // AJAX call to get all registered users
    $.ajax({
      url: "api/fetch.php",
      method: "GET",
      data: {
        registeredUsers: true,
      },
      success: function (result) {
        $('#registeredUsers').html(result);

      },
      error: function () {
        // no users registered in case of error (http 400)
        $('#registeredUsers').html('<p class="centerLodky">Žádní uživatelé zaregistrováni.</p>');
      }
    })
});

$(document).on('click', '.return_homepage', function () {
  location.href = "index.php";
});




