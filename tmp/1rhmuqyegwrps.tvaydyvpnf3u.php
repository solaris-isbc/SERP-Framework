<?php echo $this->render('views/serp_header.htm',NULL,get_defined_vars(),0); ?>
<?php foreach (($snippets?:[]) as $snippet): ?>
    <?php echo $this->render($templatePath,NULL,get_defined_vars(),0); ?>
<?php endforeach; ?>
<?php echo $this->render('views/serp_footer.htm',NULL,get_defined_vars(),0); ?>
