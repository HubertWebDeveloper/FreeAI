
<?php
if(isset($_GET['section'])){
    $section = $_GET['section'];
}
?>
<div class="container-fluid row mb-3 mx-auto"style="margin-top:70px;height:85vh">
    <!-- === sidebar section ==== -->
    <div class="col-md-3 bg-dark shadow"style="border:2px solid white">
        <ul class="nav flex-column text-center">
            <li class="nav-item"style="background: <?php echo ($section == 'ctn') ? '#808b96' : 'transparent'; ?>">
                <a class="nav-link text-light" style="font-size:17px;" aria-current="page" href="creative?section=ctn">
                    <i class="bi bi-image-alt" style="font-size:28px;margin-right:10px"></i>
                    <b>Generate Content Images</b> 
                </a>
            </li>
            <hr class="text-light">
            <li class="nav-item"style="background: <?php echo ($section == 'stry') ? '#808b96' : 'transparent'; ?>">
                <a class="nav-link text-light" style="font-size:17px;" aria-current="page" href="creative?section=stry">
                    <i class="bi bi-camera-reels-fill"style="font-size:28px;margin-right:10px"></i> 
                    <b>Generate Story Teller</b> 
                </a>
            </li>
            <hr class="text-light">
            
        </ul>
    </div>
    <!-- === body section ==== -->
    <div class="col-md-9 shadow">