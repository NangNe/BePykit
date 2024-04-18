<?php

use Ramsey\Uuid\Uuid;

function generateUuidWithUniqid() {
    return Uuid::uuid4()->toString() . "-" . uniqid();
}
