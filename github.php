<?php

/*
 |----------------------------------------------------------------
 | This file is accessed by `github.php` in public directory
 |----------------------------------------------------------------
 |
 | shell_exec() function can also be used instead of back ticks
 |
 | 1. Go back one directory
 | 2. Use `git pull` to pull from GitHub
 |
 */


return `cd ..; git pull`;