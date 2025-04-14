<?php include('includes/header.php') ?>

<div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
  </div>

  <div class="carousel-inner">
    <!-- Slide 1 with Video -->
    <div class="carousel-item active" data-bs-interval="10000" style="position:relative;">
      <video src="video2.mp4" class="d-block w-100" style="height:85vh;object-fit:cover;" autoplay muted loop></video>
      <!-- Background overlay -->
      <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:1;"></div>
      <!-- Caption -->
      <div class="carousel-caption d-none d-md-block" style="z-index:2; position:absolute;">
        <h5 class="text-white">First slide label</h5>
        <p class="text-white">Some representative placeholder content for the first slide.</p>
      </div>
    </div>
  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


<?php include('includes/footer.php') ?>