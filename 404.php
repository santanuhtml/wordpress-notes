<?php get_header(); ?>
<div class="parent">
  <div class="child text-center">
    <span style='font-size:68px; line-height:normal;'>&#9785;</span>
    <h2>404</h2>
    <p><a href="<?php echo site_url(); ?>">Click Here to Visit Our Home Page</a></p>
</div>
</div>
<style>
.parent{
    position:relative;
    height:80vh;
    overflow:hidden;
} 
.child{
    position:absolute;
    left:50%;
    top:50%;
    transform:translate(-50%, -50%);
}
.child h2{
  font-size: 10vw;
  letter-spacing: -12px;
}
@media(max-width:767px){
    .child h2{
    font-size: 15vw;
  }
}
</style>
<?php get_footer(); ?>