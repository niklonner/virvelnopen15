    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="menutext">MENY</span>
            <span class="sr-only">Visa navigation på/av</span>
          </button>
          <?php $current_page = preg_replace('/.*\//','',$_SERVER['PHP_SELF'])?>

          <a class="navbar-brand" href="<?php echo $current_page=="index.php" ? "#" : "index.php"; ?>">Gothia Open 2013</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li<?php echo ($current_page=='index.php' ?
                ' class="active"><a href="#">' :
                '><a href="index.php">');?>Hem</a></li>
            <li<?php echo ($current_page=='format.php' ?
                ' class="active"><a href="#">' :
                '><a href="format.php">');?>Format</a></li>
            <li<?php echo ($current_page=='register.php' ?
                ' class="active"><a href="#">' :
                '><a href="register.php">');?>Anmälan</a></li>
            <li<?php echo ($current_page=='allresults.php' ?
                ' class="active"><a href="#">' :
                '><a href="allresults.php">');?>Starter/resultat</a></li>
            <li<?php echo ($current_page=='contact.php' ?
                ' class="active"><a href="#">' :
                '><a href="contact.php">');?>Kontakt</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
