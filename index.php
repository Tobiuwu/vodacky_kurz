<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Metadata -->
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- Links -->
  <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,600&display=swap" rel="stylesheet">
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/main.css" rel="stylesheet" />
  <link href="css/form.css" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
  <title>Vodácký kurz</title>
</head>

<body>

  <header id="home">
    <video class="video" id="ship_video" autoplay muted loop disablePictureInPicture>
      <source src="video/ship.mp4" type="video/mp4">
    </video>

    <div class="container">
      <nav id="navbar" class="navbar navbar-expand-lg navbar-light ">
        <a class="navbar-brand" href="#">
          <h5>Vítejte na stránce!</h5>
        </a>
        </button>
        <div class="collapse navbar-collapse right cima" id="navbarSupportedContent">
          <ul id="menu" class="navbar-nav mr-auto nav-right">
            <li class="nav-item ">
              <!-- Nav Link -->
              <a class="nav-link right" data-scroll href="registration.html">Registrace</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2>Rozsazení v lodích na vodáckém kurzu 2023 SPŠE Ječná</h2>
      </div>
    </div>

    <div class="row">
      <div id="registeredUsers" class="col-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nick</th>
              <th scope="col">Rok narození</th>
              <th scope="col">Kamarád na kánoj</th>
            </tr>
          </thead>
          <tbody>
            $row
          </tbody>
        </table>
      </div>

    </div>

  </div>
  <!-- Icons (FontsAwesome) -->
  <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js"></script>
  <!-- JQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>
  <!-- Scripts -->
  <script src="js/main.js"></script>

</body>

</html>