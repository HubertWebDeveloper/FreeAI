<?php include('includes/header.php') ?>
<?php
if(isset($_GET['section'])){
    $section = $_GET['section'];

    if($section == "ctn"){
        include('Sections/ImagesContent.php');
    }else if($section == "stry"){
        include('Sections/StoryContent.php');
    }else if($section == "vds"){
        include('Sections/VideoContent.php');
    }else if($section == "stryRd"){
        include('Sections/StoryReader.php');
    }else{
        include('error.php');
    }
}
?>

<?php include('includes/footer.php') ?>