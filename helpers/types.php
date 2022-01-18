<?php

// список типов контента
$sql = "SELECT id, name, type FROM types";
$types = selectRows($conn, $sql);
