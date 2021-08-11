<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/png" href="<?php echo RESOURCES . "img/favicon.png"; ?>">
        <title>.: Error 404 :.</title>
        <style type="text/css">
            @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 300;
                src: local('Roboto Light'), local('Roboto-Light'), url(../fonts/Roboto/KFOlCnqEu92Fr1MmSU5fBBc4.woff2) format('woff2');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }
            /* latin */
            @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 400;
                src: local('Roboto'), local('Roboto-Regular'), url(../fonts/Roboto/KFOmCnqEu92Fr1Mu4mxK.woff2) format('woff2');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }
            /* latin */
            @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 500;
                src: local('Roboto Medium'), local('Roboto-Medium'), url(../fonts/Roboto/KFOlCnqEu92Fr1MmEU9fBBc4.woff2) format('woff2');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }
            body {
                background-image: url(<?php echo RESOURCES . "img/error_general.jpg"; ?>);
                font-family: "Roboto", sans-serif;
                font-size: 0.875rem;
                background-position: center center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                background-color: rgb(0,91,170);
            }
            .content {
                position:absolute;
                top:45%;
                left:50%;
                margin-left:-50px;
                margin-top:-50px;
            }
            .content > h1 {
                font-size: 40px;
                color: rgb(247,148,30);
            }
            .content > p {
                font-size: 20px;
                font-weight: bold;
                color: rgb(0,91,170);
            }
        </style>
    </head>
    <body>
        <div class="content">
            <h1><?php echo $heading; ?></h1>
            <p><?php echo $message; ?></p>
        </div>
    </body>
</html>