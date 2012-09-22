<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2">
        <title></title>
        <style type="text/css">
            .errorMessageBox {
                display: block;
                background-color: lightsalmon;
                border-color: red;
                border-style: solid;
                border-width: 1px;
                width: 50%;
                padding-top: 30px;
                padding-bottom: 30px;
                margin: 100px auto;
                text-align: center;
            }

        </style>
    </head>
    <body>
        <div class="errorMessageBox">
            <p>
                <?
                    echo $this->m_sMessage;
                ?>
            </p>
        </div>
        
    </body>
</html>
