<?php

namespace Ybreaka98\EbtekarDCB\Exceptions;

use Exception;

class EbtekarException extends Exception
{
}

class AuthenticationException extends EbtekarException
{
}

class ValidationException extends EbtekarException
{
}
