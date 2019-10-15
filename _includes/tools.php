<?php

function checkVarCharLength($char)
{
    if(strlen($char) < 255)
    {
        return true;
    }
    return false;
}
