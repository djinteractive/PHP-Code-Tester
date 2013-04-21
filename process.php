<?php

// Configuration
ini_set("display_errors",true);
set_time_limit (5);
set_include_path(__dir__);

// Retrieve code
$code = $_POST["code"];

// Run code
eval($code);