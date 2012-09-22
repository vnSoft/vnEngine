<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>vEngine</title>
        <?
        echo $this->m_sMetadata;

        foreach ($this->m_styles as $style)
            echo "<link rel='stylesheet' media='screen' href='$style'/>";

        foreach ($this->m_scripts as $script)
            echo "<script language='javascript' type='text/javascript' src='$script'></script>";

        echo "<script>setRoot('" . ROOT . "');</script>";
        ?>

    </head>
    <body>
        <div id="async_window"></div>
        <div id="main">


            <?
            if (!empty($this->m_staticViews['menu']))
                $this->m_staticViews['menu']->render();
            ?>


            <div id="views_area">
                <?
                if ($this->m_bExceptionOccured)
                    echo $this->m_exception->getMessage();

                foreach ($this->m_views as $view) {
                    $view->render();
                }
                ?>
            </div>

        </div>

    </body>
</html>