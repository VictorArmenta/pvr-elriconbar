<?php
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=documento.pdf");
readfile("assets/prosoft_pvr.pdf");