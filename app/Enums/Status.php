<?php

namespace App\Enums;

enum Status: int
{
    case QUEUED = 1;
    case PROCESSING_BY_QUEUE = 2;
    case PROCESSING_BY_AWS = 3;
    case UPDATING_MODEL = 4;
    case PROCESSED = 5;
}
