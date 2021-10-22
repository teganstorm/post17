<style>
    #errormsg {
        position: relative;
        text-align: center;
        font-family: Arial, sans-serif;
        color: red;
        font-weight: bold;
        font-size: 14px;
    }
</style>
<?php  if (count($errors) > 0) : ?>
  <div class="error">
  	<?php foreach ($errors as $error) : ?>
  	  <p id="errormsg"><?php echo $error ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>