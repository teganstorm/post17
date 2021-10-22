<html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<!-- tried adding button that would stick to the top of the footer "add post" user simple presses button at the bottom to be taken to newpost.php simply convinient for phone users COULD ALSO BE AN IMAGE and we could align it on top of footer in the middle (like a little plus button)-->
<!-- <div class="d-grid gap-2">
  <button id="createpost" onclick="location.href='newpost.php'" class="btn btn-warning" type="button">Add Post</button>
</div> -->
<nav class="navbar fixed-bottom navbar-light bg-success bg-gradient">
  <div class="container-fluid ">
      
    <a class="navbar-brand" href="#">© Fringelogic Group 17  </a> 
    
    <?php include 'backbutton.php'; ?>
    
    <button class="btn btn-warning" id="createpost" onclick="location.href='newpost.php'">Create New Post</button>
    <a class="navbar-text navbar-right">Powered by Bootstrap</a>
    
  </div>
</nav>
</html>